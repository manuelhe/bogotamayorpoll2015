<?php
namespace Mas;

/**
 * Layout Helper Class
 *
 * @author manuel.he@gmail.com
 */
class LayoutHelper {
  private $templater;
  private $config;

  public function __construct(\ArrayAccess $config) {
    $this->config = $config;
    $this->templater = new \Mas\Template($config['config']['templatesDir']);
    $this->templater->setVar('config', $config['config']);
  }

  private function getCompiledHeader() {
    $this->templater->setVar('socialTags', $this->getCompiledSocialTags());
    return $this->templater->parse('header.tpl.php');
  }

  private function getCompiledSocialTags() {
    return $this->templater->parse('social.tpl.php');
  }

  private function getCompiledFooter() {
    return $this->templater->parse('footer.tpl.php');
  }

  private function getMenuItems() {
    return [
      [
        'url' => $this->config['config']['baseUrl'],
        'title' => 'Resultados',
      ],
      [
        'url' => $this->config['config']['baseUrl'] . '/about',
        'title' => 'Sobre nosotros'
      ],
      [
        'url' => $this->config['config']['baseUrl'] . '/faq',
        'title' => 'Preguntas frecuentes'
      ]
    ];
  }
    
  public function render($templateName, $vars) {
    $content = '';
    if (!$templateName) {
      throw new InvalidArgumentException('templateName argument is required.');
    }
    $this->templater->setVar('menuItems', $this->getMenuItems());
    $this->templater->setVars($vars);
    $content .= $this->getCompiledHeader();
    $content .= $this->templater->parse($templateName);
    return $content . $this->getCompiledFooter();
  }
}
