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
    $options = ['term_1', 'term_2'];

    $form['vocab'] = array(
        '#type' => 'select',
        '#title' => t('Content Styles'),
        '#multiple' => true,
        '#options' => $options,
        '#description' => t('Add created vocab/term styles.'),
      );
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
