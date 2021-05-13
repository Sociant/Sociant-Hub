<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContactFormType;
use Symfony\Component\HttpFoundation\Request;
use ReCaptcha\ReCaptcha;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class StandaloneController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request)
    {
        $loginError = null;

        if($request->query->has('login_error'))
            switch($request->query->get('login_error')) {
                case 'token': $loginError = 'Invalid token, please try again.'; break;
                case 'banned': $loginError = 'Your account has been banned from Sociant Hub.'; break;
            }

        return $this->render('standalone/index.html.twig', [
            'ignore' => true,
            'loginError' => $loginError
        ]);
    }

    /**
     * @Route("/privacy-policy", name="privacy_policy")
     */
    public function privacy(Request $request)
    {
        return $this->render('standalone/privacy.html.twig',[
            "fromApp" => $request->query->has("from-app")
        ]);
    }
    
    /**
     * @Route("/legal-disclosure", name="legal_disclosure")
     */
    public function imprint(Request $request)
    {
        return $this->render('standalone/legal.html.twig',[
            "fromApp" => $request->query->has("from-app")
        ]);
    }
    
    /**
     * @Route("/feedback", name="feedback")
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, MailerInterface $mailer)
    {

        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        $submitted = false;

        if($form->isSubmitted() && $form->isValid() &&
            (new ReCaptcha($this->getParameter("recaptcha_secret")))->verify($request->request->get("g-recaptcha-response",""),$request->getClientIp())->isSuccess()) {
            
            $recipientEmail = $this->getParameter("recipient_email");

            $email = (new TemplatedEmail())
                ->from($recipientEmail)
                ->to($recipientEmail)
                ->subject($form->get("type")->getData())
                ->htmlTemplate('emails/contact.html.twig')
                ->textTemplate('emails/contact.txt.twig')
                ->replyTo($form->get("email")->getData())
                ->context([
                    'name' => $form->get("name")->getData(),
                    'mail' => $form->get("email")->getData(),
                    'type' => $form->get("type")->getData(),
                    'message' => $form->get("message")->getData()
                ]);

            $mailer->send($email);

            $submitted = true;
        }

        return $this->render('standalone/contact.html.twig', [
            'submitted' => $submitted,
            'form' => $form->createView(),
            'recaptcha_public' => $this->getParameter("recaptcha_public")
        ]);
    }
}
