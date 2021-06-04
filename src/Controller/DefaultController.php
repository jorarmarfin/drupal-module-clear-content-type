<?php

namespace Drupal\clear_content_type\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class DefaultController.
 */
class DefaultController extends ControllerBase {

  /**
   * Default.
   *
   * @return string
   *   Return Hello string.
   */
  public function Default($tipo,$cantidad) {
    $data = [
      'tipo' => $tipo,
      'cantidad' => $cantidad
    ];
    $antes = $antes = $this->getCantidad($data['tipo']);
    $this->Clear($data);
    $despues = $this->getCantidad($data['tipo']);
    return new JsonResponse(['code' => 200, 'data' => $data, 'antes'=>$antes, 'despues'=>$despues]);
  }
  public function Clear($data)
  {
    $result = \Drupal::entityQuery("node")
                    ->condition("type", $data['tipo'])
                    ->accessCheck(FALSE)
                    ->range(0,$data['cantidad'])
                    ->execute();
    $storage_handler = \Drupal::entityTypeManager()->getStorage("node");
    $entities = $storage_handler->loadMultiple($result);
    $storage_handler->delete($entities);
  }
  public function getCantidad($tipo)
  {
    $query = \Drupal::entityQuery('node')->condition('type', $tipo);
    $count = $query->count()->execute();
    return $count;
  }

}
