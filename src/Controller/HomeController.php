<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', []);
    }

    #[Route('/saveFile', name: 'save_file')]
    public function saveFile(Request $request, KernelInterface $kernel): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $uploadedFilePath = $request->files->get('factory-file')->getPathname();
        $waitingDir = $this->getParameter('kernel.project_dir') . '/public/waiting';
        $factoryId = $request->get('factory_id', $user->getFactories()[0]->getId());

        if (!is_dir($waitingDir) && !mkdir($waitingDir) && !is_dir($waitingDir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $waitingDir));
        }

        rename(
            $uploadedFilePath,
            $waitingDir . '/' . $factoryId . '-' . time()
        );

        //TODO use a cron job
        $application = new Application($kernel);
        $input = new ArrayInput(['command' => 'file:import']);
        $output = new BufferedOutput();
        try {
            $application->doRun($input, $output);
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());

        }

        $this->addFlash('success', $output->fetch());

        return $this->redirectToRoute('home');
    }
}
