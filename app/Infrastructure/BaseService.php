<?php namespace App\Infrastructure;

use App\Interfaces\ServiceInterface;
use App\Interfaces\ValidatorInterface;
use Illuminate\Support\MessageBag;


class BaseService implements ServiceInterface
{
    protected ValidatorInterface|null $validator = null;
    protected MessageBag|null $errors = null;

    /**
     * Service constructor.
     */
    public function __construct()
    {
        $this->errors = new MessageBag();
    }

    /**
     * Return errors generated by validator or the code
     * @return MessageBag
     */
    public function errors() : MessageBag
    {
        if($this->validator!==null && $this->validator->errors()->isEmpty()) return $this->errors;
        else
        {
            if($this->validator!==null)
                return $this->errors->merge( $this->validator->errors() );
            else
                return $this->errors;
        }
    }

    /**
     * Return rules of validator in key position.
     * @param string|null $key
     * @return array
     */
    public function rules(string $key = null) : array
    {
        return $this->validator->getRules($key);
    }

    /**
     * Add a MessageBag object to errors for some reason
     * @param MessageBag $errors
     * @return BaseService
     */
    public function pushErrors(MessageBag $errors) : ServiceInterface
    {
        $this->errors->merge($errors);
        return $this;
    }

    /**
     * Add a message to errors with key called "error" by default.
     * @param string $message
     * @param string $key
     * @return BaseService
     */
    public function pushError(string $message, string $key = 'error') : ServiceInterface
    {
        $this->errors->add($key, $message);
        return $this;
    }

    /**
     * Clean all errors
     * @return BaseService
     */
    public function clearErrors() : BaseService
    {
        $this->errors = new MessageBag();
        return $this;
    }

    /**
     * Return a number of generated errors.
     * @return int
     */
    public function countErrors() : int
    {
        return $this->errors()->count();
    }
}
