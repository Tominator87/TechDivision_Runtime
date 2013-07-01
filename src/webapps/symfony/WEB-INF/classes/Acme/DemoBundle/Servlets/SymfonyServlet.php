<?php

namespace Acme\DemoBundle\Servlets;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use TechDivision\ServletContainer\Interfaces\Servlet;
use TechDivision\ServletContainer\Servlets\HttpServlet;
use TechDivision\ServletContainer\Interfaces\ServletConfig;
use TechDivision\ServletContainer\Interfaces\ServletRequest;
use TechDivision\ServletContainer\Interfaces\ServletResponse;

use TechDivision\Example\Entities\Sample;
use TechDivision\PersistenceContainerClient\Context\Connection\Factory;
use Symfony\Component\HttpFoundation\Session\Storage\ServletSessionStorage;

class SymfonyServlet extends HttpServlet implements Servlet {

    /**
     * @param ServletRequest $req
     * @param ServletResponse $res
     */
    public function doGet(ServletRequest $req, ServletResponse $res) {

        // If you don't want to setup permissions the proper way, just uncomment the following PHP line
        // read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
        // umask(0000);

        // This check prevents access to debug front controllers that are deployed by accident to production servers.
        // Feel free to remove this, extend it, or make something more sophisticated.
        /*
        if (isset($_SERVER['HTTP_CLIENT_IP'])
            || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
            || !in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1'))
        ) {
            header('HTTP/1.0 403 Forbidden');
            exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
        }
        */

        /*
        $req->getSession()->start();

        if ($req->getSession()->hasKey('testKey') === false) {
            $req->getSession()->putData('testKey', 'TestData');
        } else {
            $value = $req->getSession()->getData('testKey');
            error_log("Found '$value' for session key 'testKey'");
        }
        */

        $loader = require_once 'webapps/symfony/bootstrap.php.cache';

        $kernel = new \AppKernel('dev', true);
        $kernel->loadClassCache();

        Request::enableHttpMethodParameterOverride();
        // $request = Request::createFromGlobals();

        $request = Request::create($req->getRequestUri(), $req->getMethod(), $req->getParameterMap(), array(), array(), $req->getServerVars());

        $sessionOptions = array();
        $session = new Session(new ServletSessionStorage($sessionOptions, $req->getSession()));

        $kernel->boot();
        $kernel->getContainer()->set('session', $session);

        $response = $kernel->handle($request);

        // $response->send();
        $kernel->terminate($request, $response);

        // $res->addHeader('Set-Cookie', 'APPSERVER_SESSID=' . uniqid());
        $res->setContent($response->getContent());
    }

    public function doPost(ServletRequest $req, ServletResponse $res) {
        $this->doGet($req, $res);
    }
}