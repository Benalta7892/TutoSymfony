<?php

namespace App\Form;

use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;
use App\Entity\Recipe;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\String\Slugger\SluggerInterface;

class FormListenerFactory
{

  public function __construct(private SluggerInterface $slugger)
  {
  }


  public function autoSlug(string $field): callable
  {
    return function (PreSubmitEvent $event) use ($field) {
      $data = $event->getData();
      if (empty($data['slug'])) {
        $slugger = new AsciiSlugger();
        $data['slug'] = strtolower($this->slugger->slug($data[$field]));
        $event->setData($data);
      }
    };
  }

  public function timestamps(): callable
  {
    return function (PostSubmitEvent $event) {
      $data = $event->getData();

      $data->setUpdatedAt(new \DateTimeImmutable());
      if (!$data->getId()) {
        $data->setCreatedAt(new \DateTimeImmutable());
      }
    };
  }
}
