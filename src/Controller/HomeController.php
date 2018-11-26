<?php
/**
 * Created by PhpStorm.
 * User: Walid
 * Date: 23/11/2018
 * Time: 18:00
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @Route("/hello/{last_name}/{age}", name="hello_name", requirements={"age"="\d+"})
     * @Route("/hello", name="hello")
     * @return void
     */
    public function Hello($last_name = "anonyme", $age = 0)
    {
        return $this->render(
            'hello.html.twig',
            [
               'last_name' => $last_name,
               'age' => $age
            ]);
        //return new Response('Bonjour Mr ' . $last_name . ' vous avez ' . $age);
    }
    /**
     * @Route("/", name="homepage")
     */
    public function Home()
    {
        $prenoms = array(
            20 => 'khaled', 25 => 'hichem', 30 => 'anouar'
        );
        return $this->render(
            'home.html.twig',
            [
                'title' => "Bonjour à vous !",
                'age' => 31,
                'prenoms' => $prenoms
            ]
        );
    }
}