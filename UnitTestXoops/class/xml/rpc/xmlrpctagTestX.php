<?php

abstract class XoopsXmlRpcDocument
{
    public function test_add()
    {
    }

    abstract function test_render();

}

class XoopsXmlRpcResponse extends XoopsXmlRpcDocument
{
    public function test_render()
    {
    }
}

class XoopsXmlRpcRequest extends XoopsXmlRpcDocument
{
    public function test___construct()
    {
    }

    public function test_render()
    {
    }
}

abstract class XoopsXmlRpcTag
{
    public function test_encode()
    {
    }

    public function test_setFault($fault = true)
    {
    }

    public function test_isFault()
    {
    }

    abstract function test_render();
}

class XoopsXmlRpcFault extends XoopsXmlRpcTag
{
    public function test___construct()
    {
    }

    public function test_render()
    {
    }
}

class XoopsXmlRpcInt extends XoopsXmlRpcTag
{
    public function test___construct()
    {
    }

    public function test_render()
    {
    }
}

class XoopsXmlRpcDouble extends XoopsXmlRpcTag
{
    public function test___construct()
    {
    }

    public function test_render()
    {
    }
}

class XoopsXmlRpcBoolean extends XoopsXmlRpcTag
{
    public function test___construct()
    {
    }

    public function test_render()
    {
    }
}

class XoopsXmlRpcString extends XoopsXmlRpcTag
{
    public function test___construct()
    {
    }

    public function test_render()
    {
    }
}

class XoopsXmlRpcDatetime extends XoopsXmlRpcTag
{
    public function test___construct()
    {
    }

    public function test_render()
    {
    }
}

class XoopsXmlRpcBase64 extends XoopsXmlRpcTag
{
    public function test___construct()
    {
    }

    public function test_render()
    {
    }
}

class XoopsXmlRpcArray extends XoopsXmlRpcTag
{
    public function test_add()
    {
    }

    public function test_render()
    {
    }
}

class XoopsXmlRpcStruct extends XoopsXmlRpcTag
{
    public function test_add()
    {
    }

    public function test_render()
    {
    }
}
