namespace Drupal\restrict_vehicles\EventSubscriber;

use Drupal\Core\Session\AccountInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class VehicleAccessSubscriber implements EventSubscriberInterface {
  protected $currentUser;

  public function __construct(AccountInterface $current_user) {
    $this->currentUser = $current_user;
  }

  public static function getSubscribedEvents() {
    return [
      KernelEvents::REQUEST => 'onRequest',
    ];
  }

  public function onRequest(RequestEvent $event) {
    $request = $event->getRequest();

    if ($request->attributes->get('_route') === 'entity.node.canonical') {
      $node = $request->attributes->get('node');

      if ($node && $node->bundle() === 'vehicle') {
        $year = $node->get('field_vehicle_year')->value;
        if ($year == 2020) {
          throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
      }
    }
  }
}
