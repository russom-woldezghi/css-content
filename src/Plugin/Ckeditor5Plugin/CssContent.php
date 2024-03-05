<?php

declare(strict_types=1);

namespace Drupal\css_content\Plugin\CKEditor5Plugin;

use Drupal\ckeditor5\Plugin\CKEditor5PluginConfigurableTrait;
use Drupal\ckeditor5\Plugin\CKEditor5PluginDefault;
use Drupal\ckeditor5\Plugin\CKEditor5PluginConfigurableInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\editor\EditorInterface;
use Drupal\ckeditor5\Plugin\CKEditor5Plugin\Style;
use Drupal\ckeditor5\Plugin\CKEditor5PluginElementsSubsetInterface;

/**
 * CKEditor 5 CssContent plugin.
 */
class CssContent extends CKEditor5PluginDefault implements CKEditor5PluginConfigurableInterface, CKEditor5PluginElementsSubsetInterface {

  use CKEditor5PluginConfigurableTrait;


  use CKEditor5PluginConfigurableTrait;

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['styles'] = [
      '#title' => $this->t('Link Styles'),
      '#type' => 'textarea',
      '#description' => $this->t('A list of classes that will be provided as Link Styles. Enter one or more classes on each line in the format: a.classA.classB|Label. Example: a.btn|Button. Advanced example: a.btn.large-button|Large Button.<br />These link styles should be available in your theme\'s CSS file.'),
    ];
    if (!empty($this->configuration['styles'])) {
      $as_selectors = '';
      foreach ($this->configuration['styles'] as $style) {
        [$tag, $classes] = self::getTagAndClasses(HTMLRestrictions::fromString($style['element']));
        $as_selectors .= sprintf("%s.%s|%s\n", $tag, implode('.', $classes), $style['label']);
      }
      $form['styles']['#default_value'] = $as_selectors;
    }

    return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
      // $form_state->setErrorByName('vocab', 'Vocabulary not found.');
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['vocab'] = $form_state->getValue('vocab');
    $this->configuration['styles'] = $form_state->getValue('styles');

  }

  /**
   * {@inheritdoc}
   */
  public function getDynamicPluginConfig(array $static_plugin_config, EditorInterface $editor): array {
    $static_plugin_config['css_content']['vocab'] = $this->configuration['vocab'];
    $static_plugin_config['css_content']['styles'] = $this->configuration['styles'];
    return $static_plugin_config;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'vocab' => 'css_content',
      'styles' => [],
    ];
  }

   /**
   * @inheritDoc
   */
  public function getElementsSubset(): array {
    if (empty($this->configuration['styles'])) {
      return [];
    }
    return ['<>'];
  }


}
