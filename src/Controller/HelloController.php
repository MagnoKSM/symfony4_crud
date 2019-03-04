<?php 

namespace App\Controller;

use App\Entity\Produto;
use Doctrine\DBAL\Types\DecimalType;
use Doctrine\DBAL\Types\Type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Tests\Extension\Core\Type\TextTypeTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends Controller
{

    /**
     * @return Response
     * 
     * @Route("hello_world")
     */
    public function world() 
    {
        return new Response(
            "<html><body><h1>HELLO WORLD!!<h1></body></html>"
        );
    }

    /**
     * @return Response
     * 
     * @Route("mostrar-mensagem")
     */
    public function mensagem()
    {
        return $this->render("hello/mensagem.html.twig", [
            'mensagem' => "Ola School coding"
        ]);
    }


    /**
     * @return Response
     *
     * @Route("cadastra-produto")
     */
    public function produto()
    {
        $em = $this->getDoctrine()->getManager();

        $produto = new Produto();
        $produto->setName("XBox-One");
        $produto->setPrice(1400.00);

        $em->persist($produto);
        $em->flush();

        return new Response("O produto" . $produto->getId() . " Foi criado com sucesso.");
    }

    /**
     * @return Response
     *
     * @Route("formulario")
     */
    public function formulario(Request $request)
    {
        $produto = new Produto();

        $form = $this->createFormBuilder($produto)
            ->add('name', TextType::class, ['required' => true])
            ->add('price', TextType::class)
            ->add( 'descricao', TextType::class)
            ->add('enviar', SubmitType::class, ['label' => 'Salvar'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return new Response('Formulario esta ok!!!');
        }

        return $this->render('hello/formulario.html.twig', [
            'form' => $form->createView()
        ]);
    }

}