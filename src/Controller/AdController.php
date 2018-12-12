<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\Common\Persistence\ObjectManager;
use http\Env\Response;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
        ]);
    }

    /**
     * Creation d'une annonce
     *
     * @Route("/ads/new", name="create_annonce")
     *
     * @return Form
     */
    public function create(Request $request, ObjectManager $manager)
    {
        $ad = new Ad();

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            foreach ($ad->getImages() as $image)
            {
                $image->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                '<strong>L\'annonce a été bien créer</strong>'
            );

            return $this->redirectToRoute('ads_show', array(
                'slug' => $ad->getSlug()
            ));
        }

        return $this->render("ad/new.html.twig", [
            "form_ad" => $form->createView()
        ]);

    }

    /**
     * Permet d'afficher un article specifique
     *
     * @Route("/ads/{slug}", name="ads_show", )
     *
     */
    public function show(Ad $ad)
    {
        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }

    /**
     * permet de modifier une annonce
     *
     * @Route("/ads/{slug}/edit", name="ads_edit")
     *
     * @return Response
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager)
    {

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            foreach ($ad->getImages() as $image)
            {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();
            //die("test");
            $this->addFlash(
                'success',
                '<strong>L\'annonce a été bien modifier</strong>'
            );

            return $this->redirectToRoute('ads_show', array(
                'slug' => $ad->getSlug()
            ));
        }

        return $this->render("ad/edit.html.twig", [
            'form' => $form->createView(),
            'annonce' => $ad
        ]);
    }


}
