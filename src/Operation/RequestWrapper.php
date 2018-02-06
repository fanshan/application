<?php

namespace ObjectivePHP\Application\Operation;


use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Application\Exception;
use ObjectivePHP\Application\Middleware\AbstractMiddleware;
use ObjectivePHP\Cli\Request\CliRequest;
use ObjectivePHP\Message\Request\HttpRequest;
use Psr\Http\Message\StreamInterface;
use Zend\Diactoros\PhpInputStream;
use Zend\Diactoros\ServerRequestFactory;

/**
 * Class RequestWrapper
 *
 * @package ObjectivePHP\Application\Operation\Common
 */
class RequestWrapper extends AbstractMiddleware
{

    /** @var StreamInterface|resource|string */
    protected $stream;

    /**
     * Get Stream
     *
     * @return StreamInterface|resource|string
     */
    public function getStream()
    {
        if (empty($this->stream)) {
            $this->stream = new PhpInputStream();
        }

        return $this->stream;
    }

    /**
     * Set Stream
     *
     * @param StreamInterface|resource|string $stream
     *
     * @return $this
     */
    public function setStream($stream)
    {
        $this->stream = $stream;

        return $this;
    }

    /**
     * @param ApplicationInterface $app
     *
     * @throws Exception
     */
    public function run(ApplicationInterface $app)
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            $server  = ServerRequestFactory::normalizeServer($_SERVER);
            $files   = ServerRequestFactory::normalizeFiles($_FILES);
            $headers = ServerRequestFactory::marshalHeaders($server);

            $request = new HttpRequest(
                ServerRequestFactory::marshalUriFromServer($server, $headers),
                ServerRequestFactory::get('REQUEST_METHOD', $server, 'GET'),
                'php://input',
                $headers,
                $server,
                $files,
                $_COOKIE,
                $_GET,
                $_POST,
                self::marshalProtocolVersion($server)
            );

            $request->setGet($_GET);
            $request->setPost($_POST);

            if (isset($_FILES)) {
                $request->getParameters()->setFiles($_FILES);
            }
        } elseif (class_exists(CliRequest::class)) {
            $request = new CliRequest($_SERVER['argv'][1] ?? null);
        } else {
            throw new Exception("No request matches current environment");
        }

        $app->setRequest($request);
    }

    /**
     * Return HTTP protocol version (X.Y)
     *
     * This is a copy of original method which is marked as private in ServerRequestFactory class
     *
     * @param array $server
     * @return string
     */
    protected static function marshalProtocolVersion(array $server)
    {
        if (! isset($server['SERVER_PROTOCOL'])) {
            return '1.1';
        }

        if (! preg_match('#^(HTTP/)?(?P<version>[1-9]\d*(?:\.\d)?)$#', $server['SERVER_PROTOCOL'], $matches)) {
            throw new \UnexpectedValueException(sprintf(
                'Unrecognized protocol version (%s)',
                $server['SERVER_PROTOCOL']
            ));
        }

        return $matches['version'];
    }
}
