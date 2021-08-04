<?php

namespace DorsetDigital\SimpleGTag;

use Broarm\CookieConsent\CookieConsent;
use SilverStripe\Admin\AdminRootController;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\Middleware\HTTPMiddleware;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Manifest\ModuleLoader;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\View\ArrayData;
use SilverStripe\View\HTML;

class GTagMiddleware implements HTTPMiddleware
{

    use Injectable;

    use Configurable;

    /**
     * @config
     *
     * Domain name of GTM
     * @var string
     */
    private static $gtm_domain = 'www.googletagmanager.com';

    /**
     * Holds the GTM ID
     * @var string
     */
    private $gtm_id = null;

    /**
     * Process the request
     * @param HTTPRequest $request
     * @param $delegate
     * @return
     */
    public function process(HTTPRequest $request, callable $delegate)
    {

        $response = $delegate($request);

        if (($this->getIsAdmin($request) === true) || ($this->getCookiesOK() !== true)) {
            return $response;
        }

        $config = SiteConfig::current_site_config();
        $this->gtm_id = $config->GTMID;
        if ($this->gtm_id != "") {
            $this->addBodyTag($response);
            $this->addHeadTag($response);
            $this->addPrefetch($response);
        }
        return $response;
    }

    /**
     * Determine whether the website is being viewed from an admin protected area or not
     * (shamelessly stolen from https://github.com/silverstripe/silverstripe-subsites)
     *
     * @param HTTPRequest $request
     * @return bool
     */
    private function getIsAdmin(HTTPRequest $request)
    {
        $adminPaths = static::config()->get('admin_url_paths');
        $adminPaths[] = AdminRootController::config()->get('url_base') . '/';
        $adminPaths[] = 'dev/';
        $currentPath = rtrim($request->getURL(), '/') . '/';
        foreach ($adminPaths as $adminPath) {
            if (substr($currentPath, 0, strlen($adminPath)) === $adminPath) {
                return true;
            }
        }
        return false;
    }

    /**
     * If the cookie consent module is installed, check that we have analytics allowed
     * @return bool
     * @throws \Exception
     */
    private function getCookiesOK() {
        $module = ModuleLoader::getModule('bramdeleeuw/cookieconsent');
        if ($module) {
            if (!CookieConsent::check('Analytics')) {
                return false;
            }
        }
        return true;
    }

    private function addBodyTag(&$response)
    {
        $data = ArrayData::create(['GTMID' => $this->gtm_id]);
        $tag = $data->renderWith('BodySnippet');
        $body = $response->getBody();
        $pattern = '/\<body.*\>/';
        $replace = '${0}' . "\n" . $tag;
        $newBody = preg_replace($pattern, $replace, $body);
        $response->setBody($newBody);
    }

    private function addHeadTag(&$response)
    {
        $data = ArrayData::create(['GTMID' => $this->gtm_id]);
        $tag = $data->renderWith('HeadSnippet');
        $body = $response->getBody();
        $body = str_replace('<head>', "<head>" . $tag, $body);
        $response->setBody($body);
    }

    private function addPrefetch(&$response)
    {
        $atts = [
            'rel' => 'dns-prefetch',
            'href' => $this->config()->get('gtm_domain')
        ];
        $pfTag = "\n" . HTML::createTag('link', $atts) . "\n";
        $body = $response->getBody();
        $body = str_replace('<head>', "<head>" . $pfTag, $body);
        $response->setBody($body);
    }

}
