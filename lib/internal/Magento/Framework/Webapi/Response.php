<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\Webapi;

use Magento\Framework\App\Response\HttpInterface;
use Magento\Framework\ObjectManager\ResetAfterRequestInterface;

/**
 * Web Api response
 */
class Response extends \Magento\Framework\HTTP\PhpEnvironment\Response implements
    HttpInterface,
    ResetAfterRequestInterface
{
    /**
     * Character set which must be used in response.
     */
    public const RESPONSE_CHARSET = 'utf-8';

    /**#@+
     * Default message types.
     */
    public const MESSAGE_TYPE_SUCCESS = 'success';

    public const MESSAGE_TYPE_ERROR = 'error';

    public const MESSAGE_TYPE_WARNING = 'warning';

    /**#@- */

    /**#@+
     * Success HTTP response codes.
     */
    public const HTTP_OK = 200;

    /**
     * @var array
     */
    protected $_messages = [];

    /**
     * Set header appropriate to specified MIME type.
     *
     * @param string $mimeType MIME type
     * @return $this
     */
    public function setMimeType($mimeType)
    {
        return $this->setHeader('Content-Type', "{$mimeType}; charset=" . self::RESPONSE_CHARSET, true);
    }

    /**
     * Add message to response.
     *
     * @param string $message
     * @param string $code
     * @param array $params
     * @param string $type
     * @return $this
     */
    public function addMessage($message, $code, $params = [], $type = self::MESSAGE_TYPE_ERROR)
    {
        $params['message'] = $message;
        $params['code'] = $code;
        $this->_messages[$type][] = $params;
        return $this;
    }

    /**
     * Has messages.
     *
     * @return bool
     */
    public function hasMessages()
    {
        return (bool)count($this->_messages) > 0;
    }

    /**
     * Return messages.
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->_messages;
    }

    /**
     * Clear messages.
     *
     * @return $this
     */
    public function clearMessages()
    {
        $this->_messages = [];
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function _resetState(): void
    {
        $this->clearMessages();
        parent::_resetState();
    }
}
