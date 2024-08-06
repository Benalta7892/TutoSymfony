<?php

namespace App\MessageHandler;

use App\Message\RecipePDFMessage;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
// use Symfony\Component\Process\Exception\ProcessFailedException;
// use Symfony\Component\Routing\Generator\UrlGenerator;
// use Symfony\Component\Process\Process;
// use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsMessageHandler]
final class RecipePDFMessageHandler
{

  public function __construct(
    #[Autowire('%kernel.project_dir%/public/pdfs')]
    private readonly string $path
    // ,
    // private readonly UrlGeneratorInterface $urlGenerator
  ) {
  }


  public function __invoke(RecipePDFMessage $message): void
  {
    file_put_contents($this->path . '/' . $message->id . '.pdf', '');
    // $process = new Process([
    //   'curl',
    //   '--request',
    //   'POST',
    //   'http://localhost:3000/forms/chromium/convert/url',
    //   '--form',
    //   'url=' . $this->urlGenerator->generate('recipe.show', ['id' => $message->id], UrlGeneratorInterface::ABSOLUTE_URL),
    //   '-o',
    //   $this->path . '/' . $message->id . '.pdf'
    // ]);
    // $process->run();
    // if (!$process->isSuccessful()) {
    //   throw new ProcessFailedException($process);
    // }
  }
}
