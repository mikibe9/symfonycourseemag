<?php
/**
 * Created by PhpStorm.
 * User: mihaela.aldea
 * Date: 2015-06-09
 * Time: 18:17
 */

namespace AppBundle\Event\Listener;

use Doctrine\ORM\Event\OnFlushEventArgs;

class SoftDelete
{
    public function onFlush(OnFlushEventArgs $args)
    {
        $unitOfWork = $args->getEntityManager()->getUnitOfWork();
        foreach ($unitOfWork->getScheduledEntityDeletions() as $entity) {
            if (is_callable(array($entity, 'setDeleted'))) {
                $entity->setDeleted(true);
                $unitOfWork->propertyChanged($entity, 'deleted', false, true);
                $unitOfWork->scheduleExtraUpdate(
                    $entity,
                    array(
                        'deleted' => array(false, true)
                    )
                );
                $args->getEntityManager()->persist($entity);
            }
        }
    }
} 