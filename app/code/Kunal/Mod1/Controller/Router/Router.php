<?php

namespace Kunal\Mod1\Controller\Router;

use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\Action\Redirect;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\UrlInterface;

class Router implements RouterInterface
{
    private ActionFactory $actionFactory;
    private ResponseInterface $response;
    private UrlInterface $url;

    public function __construct(
        ActionFactory $actionFactory,
        ResponseInterface $response,
        UrlInterface $url
    ) {
        $this->actionFactory = $actionFactory;
        $this->response = $response;
        $this->url = $url;
    }

    public function match(RequestInterface $request)
    {
        $identifier = trim($request->getPathInfo(), '/');

        if (preg_match('/^([A-Z][a-z0-9]+)([A-Z][a-z0-9]+)([A-Z][a-z0-9]+)$/', $identifier, $matches)) {

            $module     = strtolower($matches[1]);
            $controller = strtolower($matches[2]);
            $action     = strtolower($matches[3]);

            // Build the canonical URL and redirect the browser to it.
            // setDispatched(true) stops the routing loop,
            // so the browser makes a fresh request to mod1/index/index which
            // the standard router handles normally.
            $targetUrl = $this->url->getUrl($module . '/' . $controller . '/' . $action);

            $this->response->setRedirect($targetUrl, 301);
            $request->setDispatched(true);

            return $this->actionFactory->create(Redirect::class);
        }

        return null;
    }
}
