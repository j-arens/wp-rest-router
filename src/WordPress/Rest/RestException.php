<?php declare(strict_types=1);

namespace Downshift\WordPress\Rest;

use \Exception;

class RestException extends Exception
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param string $msg
     * @param array $data
     */
    public function __construct(string $msg = '', array $data = [])
    {
        parent::__construct($msg);
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'type' => get_class($this),
            'message' => $this->message,
            'data' => $this->data,
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $type = get_class($this);
        $msg = $this->message;
        $data = empty($this->data) ? '' : print_r($this->data, true);
        return "[$type]: $msg\n$data";
    }
}
