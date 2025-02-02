<?php

namespace Drupal\portfolio_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;


/**
 * Let us provide a custom block to display the latest 3 Portfolio items dynamically.
 *
 * @Block(
 *   id = "portfolio_block",
 *   admin_label = @Translation("Latest Portfolio Items"),
 * )
 */
class PortfolioBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The database connection service.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs a PortfolioBlock object.
   *
   * @param array $configuration
   *   The plugin configuration.
   * @param string $plugin_id
   *   The plugin ID.
   * @param mixed $plugin_definition
   *   The plugin definition.
   * @param EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param Connection $database
   *   The database connection service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, Connection $database) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->database = $database;
  }

  /**
   * Dependency injection for services.
   *
   * @param ContainerInterface $container
   *   The service container.
   * @param array $configuration
   *   The plugin configuration.
   * @param string $plugin_id
   *   The plugin ID.
   * @param mixed $plugin_definition
   *   The plugin definition.
   *
   * @return static
   *   Returns an instance of this block.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('database')
    );
  }

  /**
   * Builds the block content.
   *
   * @return array
   *   Render array for the block.
   */
  public function build() {
    // Load the node storage service.
    $storage = $this->entityTypeManager->getStorage('node');

    // Query to get the latest 3 published portfolio items.
    $query = \Drupal::entityQuery('node')
      ->condition('status', 1) // Only published nodes
      ->condition('type', 'portfolio2') // Filter by Portfolio content type
      ->sort('created', 'DESC') // Order by newest first
      ->range(0, 3) // Limit to 3 items
      ->accessCheck(TRUE);
    
    // Execute the query to get the node IDs.
    $portfolio_ids = $query->execute();

    // If portfolio items are found, load and process them.
    if ($portfolio_ids) {
      $nodes = $storage->loadMultiple($portfolio_ids);
      $items = [];

      foreach ($nodes as $node) {
        // Fetch the Portfolio Name field value.
        // $portfolio_name = $node->get('field_portfolio_name')->getString();

        // Format each portfolio item as a list entry.
        $items[] = [
          '#markup' => $this->t('<b>@title</b>', [
            '@title' => $node->toLink()->toString(),
          ]),
        ];
      }

      // Return the formatted list as a block.
      return [
        '#theme' => 'item_list', // Use Drupal's default list theme
        '#items' => $items, // Provide the list items
        '#list_type' => 'ol', // This is to list items in Ordered list
        '#attributes' => ['class' => ['portfolio-list-items']],
        '#cache' => [
          'tags' => ['node_list'], // Ensure cache updates when nodes change
        ],
      ];
    }

    // Return a message if no portfolio items exist.
    return [
      '#markup' => $this->t('There is no ortfolio items found.'),
    ];
  }
}