<?php

namespace App\Controller;

use App\Entity\Produto;
use App\Form\ProdutoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ProdutoController extends AbstractController
{
    /**
     * @Route("/produto", name="show_products")
     * @Template("produto/index.html.twig")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $produtos = $em->getRepository(Produto::class)->findAll();

        return [
            'produtos' => $produtos
        ];
    }

    /**
     * @param Request $request
     *
     * @Route("/produto/cadastrar", name="cadastrar_produto")
     * @Template("produto/create.html.twig")
     *
     */
    public function create(Request $request)
    {
        $produto = new Produto();

        $form = $this->createForm(ProdutoType::class, $produto);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($produto);
            $em->flush();

            $this->addFlash('success', 'Product saved with success!!!');
            return $this->redirectToRoute('show_products');
        }

        return [
            'form' => $form->createView()
        ];
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Template("produto/update.html.twig")
     *
     * @Route("produto/update/{id}", name="edit_product")
     */
    public function update(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $produto = $em->getRepository(Produto::class)->find($id);

        $form = $this->createForm(ProdutoType::class, $produto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($produto);
            $em->flush();

            $this->addFlash('success', 'Product updated with success!!!');
            return $this->redirectToRoute('show_products');
        }

        return [
            'produto' => $produto,
            'form' => $form->createView()
        ];
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Template("produto/view.html.twig")
     *
     * @Route("produto/view/{id}", name="show_product")
     */
    public function view(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $produto = $em->getRepository(Produto::class)->find($id);

        $form = $this->createForm(ProdutoType::class, $produto);
        $form->handleRequest($request);

        return [
            'produto' => $produto,
            'form' => $form->createView()
        ];
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("produto/delete/{id}", name="delete_product")
     */
    public function delete(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $produto = $em->getRepository(Produto::class)->find($id);

        if (!$produto) {
            $message = "Product not found!";
            $typeMessage = "warning";
        } else {
            $em->remove($produto);
            $em->flush();
            $message = "Product deleted success!";
            $typeMessage = "success";
        }

        $this->addFlash($typeMessage, $message);
        return $this->redirectToRoute('show_products');
    }
}
