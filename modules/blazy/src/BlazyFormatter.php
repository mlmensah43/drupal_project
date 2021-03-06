<?php

namespace Drupal\blazy;

/**
 * Implements BlazyFormatterInterface.
 */
class BlazyFormatter extends BlazyManager implements BlazyFormatterInterface {

  /**
   * The first image item found.
   *
   * @var object
   */
  protected $firstItem = NULL;

  /**
   * Checks if image dimensions are set.
   *
   * @var array
   */
  private $isImageDimensionSet;

  /**
   * Checks if Responsive image dimensions are set.
   *
   * @var array
   */
  private $isResponsiveImageDimensionSet;

  /**
   * {@inheritdoc}
   */
  public function buildSettings(array &$build, $items) {
    $settings = &$build['settings'];
    $this->getCommonSettings($settings);

    $count          = $items->count();
    $field          = $items->getFieldDefinition();
    $entity         = $items->getEntity();
    $entity_type_id = $entity->getEntityTypeId();
    $entity_id      = $entity->id();
    $bundle         = $entity->bundle();
    $field_name     = $field->getName();
    $field_clean    = str_replace("field_", '', $field_name);
    $view_mode      = empty($settings['current_view_mode']) ? '_custom' : $settings['current_view_mode'];
    $namespace      = $settings['namespace'];
    $id             = isset($settings['id']) ? $settings['id'] : '';
    $gallery_id     = "{$namespace}-{$entity_type_id}-{$bundle}-{$field_clean}-{$view_mode}";
    $id             = Blazy::getHtmlId("{$gallery_id}-{$entity_id}", $id);
    $internal_path  = $absolute_path = NULL;

    // Deals with UndefinedLinkTemplateException such as paragraphs type.
    // @see #2596385, or fetch the host entity.
    if (!$entity->isNew() && method_exists($entity, 'hasLinkTemplate')) {
      if ($entity->hasLinkTemplate('canonical')) {
        $url = $entity->toUrl();
        $internal_path = $url->getInternalPath();
        $absolute_path = $url->setAbsolute()->toString();
      }
    }

    $settings['bundle']         = $bundle;
    $settings['cache_metadata'] = ['keys' => [$id, $count]];
    $settings['cache_tags'][]   = $entity_id . ':' . $entity_id;
    $settings['caption']        = empty($settings['caption']) ? [] : array_filter($settings['caption']);
    $settings['content_url']    = $settings['absolute_path'] = $absolute_path;
    $settings['count']          = $count;
    $settings['entity_id']      = $entity_id;
    $settings['entity_type_id'] = $entity_type_id;
    $settings['gallery_id']     = str_replace('_', '-', $gallery_id . '-' . $settings['media_switch']);
    $settings['id']             = $id;
    $settings['internal_path']  = $internal_path;
    $settings['resimage']       = !empty($settings['responsive_image']) && !empty($settings['responsive_image_style']);
    $settings['resimage']       = $settings['resimage'] ? $this->entityLoad($settings['responsive_image_style'], 'responsive_image_style') : FALSE;
    $settings['use_field']      = !$settings['lightbox'] && isset($settings['third_party'], $settings['third_party']['linked_field']) && !empty($settings['third_party']['linked_field']['linked']);

    // Bail out if Vanilla mode is requested.
    if (!empty($settings['vanilla'])) {
      $settings = array_filter($settings);
      return;
    }

    // Don't bother if using Responsive image.
    // @todo remove custom breakpoints anytime before 2.x.
    $settings['breakpoints'] = isset($settings['breakpoints']) && empty($settings['unbreakpoints']) && empty($settings['responsive_image_style']) ? $settings['breakpoints'] : [];
    BlazyBreakpoint::cleanUpBreakpoints($settings);

    // Lazy load types: blazy, and slick: ondemand, anticipated, progressive.
    $settings['blazy'] = !empty($settings['blazy']) || !empty($settings['background']) || $settings['resimage'] || $settings['breakpoints'];
    $settings['lazy']  = $settings['blazy'] ? 'blazy' : (isset($settings['lazy']) ? $settings['lazy'] : '');
    $settings['lazy']  = empty($settings['is_preview']) ? $settings['lazy'] : '';

    // @todo remove enforced (BC), since now works for Responsive image too.
    if (isset($settings['ratio']) && $settings['ratio'] == 'enforced') {
      $settings['ratio'] = 'fluid';
    }
  }

  /**
   * {@inheritdoc}
   */
  public function preBuildElements(array &$build, $items, array $entities = []) {
    $this->buildSettings($build, $items);
    $settings = &$build['settings'];

    // Pass first item to optimize sizes this time.
    if (isset($items[0]) && $item = $items[0]) {
      $this->extractFirstItem($settings, $item, reset($entities));
    }

    // Sets dimensions once, if cropped, to reduce costs with ton of images.
    // This is less expensive than re-defining dimensions per image.
    // @todo remove first_uri for _uri for consistency.
    if (!empty($settings['_uri']) || !empty($settings['first_uri'])) {
      if (empty($settings['resimage'])) {
        $this->setImageDimensions($settings);
      }
      elseif (!empty($settings['resimage']) && $settings['ratio'] == 'fluid') {
        $this->setResponsiveImageDimensions($settings);
      }
    }

    // @todo remove if nobody uses this like everything else.
    if (!empty($settings['use_ajax'])) {
      $settings['blazy_data']['useAjax'] = TRUE;
    }

    // Allows altering the settings.
    $this->getModuleHandler()->alter('blazy_settings', $build, $items);
  }

  /**
   * {@inheritdoc}
   */
  public function postBuildElements(array &$build, $items, array $entities = []) {
    // Rebuild the first item to build colorbox/zoom-like gallery.
    $build['settings']['first_item'] = $this->firstItem;
  }

  /**
   * {@inheritdoc}
   *
   * @todo remove first_uri for _uri for consistency.
   */
  public function extractFirstItem(array &$settings, $item, $entity = NULL) {
    if ($settings['field_type'] == 'image') {
      $this->firstItem = $item;
      $settings['_uri'] = $settings['first_uri'] = ($file = $item->entity) && empty($item->uri) ? $file->getFileUri() : $item->uri;
    }
    elseif ($entity && $entity->hasField('thumbnail') && $image = $entity->get('thumbnail')->first()) {
      $this->firstItem = $image;
      $settings['_uri'] = $settings['first_uri'] = $image->entity->getFileUri();
    }

    // The first image dimensions to differ from individual item dimensions.
    BlazyUtil::imageDimensions($settings, $this->firstItem, TRUE);
  }

  /**
   * Sets dimensions once to reduce method calls, if image style contains crop.
   *
   * @param array $settings
   *   The settings being modified.
   */
  protected function setImageDimensions(array &$settings = []) {
    if (!isset($this->isImageDimensionSet[md5($settings['id'])])) {
      // If image style contains crop, sets dimension once, and let all inherit.
      if (!empty($settings['image_style']) && ($style = $this->isCrop($settings['image_style']))) {
        $settings = array_merge($settings, BlazyUtil::transformDimensions($style, $settings, TRUE));

        // Informs individual images that dimensions are already set once.
        $settings['_dimensions'] = TRUE;
      }

      // Also sets breakpoint dimensions once, if cropped.
      // @todo remove custom breakpoints anytime before 2.x.
      if (!empty($settings['breakpoints'])) {
        BlazyBreakpoint::buildDataBlazy($settings, $this->firstItem);
      }

      $this->isImageDimensionSet[md5($settings['id'])] = TRUE;
    }
  }

  /**
   * Sets dimensions once to reduce method calls for Responsive image.
   *
   * @param array $settings
   *   The settings being modified.
   */
  protected function setResponsiveImageDimensions(array &$settings = []) {
    if (!isset($this->isResponsiveImageDimensionSet[md5($settings['id'])])) {
      $srcset = [];
      foreach ($this->getResponsiveImageStyles($settings['resimage'])['styles'] as $style) {
        $settings = array_merge($settings, BlazyUtil::transformDimensions($style, $settings, TRUE));

        // In order to avoid layout reflow, we get dimensions beforehand.
        $srcset[$settings['width']] = round((($settings['height'] / $settings['width']) * 100), 2);
      }

      // Sort the srcset from small to large image width or multiplier.
      ksort($srcset);

      // Informs individual images that dimensions are already set once.
      $settings['blazy_data']['dimensions'] = $srcset;
      $settings['_dimensions'] = TRUE;

      $this->isResponsiveImageDimensionSet[md5($settings['id'])] = TRUE;
    }
  }

}
