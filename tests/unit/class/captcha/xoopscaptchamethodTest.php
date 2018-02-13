<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsCaptchaMethodTestInstance extends XoopsCaptchaMethod
{
}

class XoopsCaptchaMethodTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'XoopsCaptchaMethodTestInstance';
    
    public function test___construct()
    {
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
        $this->assertInstanceOf('XoopsCaptchaMethod', $instance);
    }
    
    public function test___construct100()
    {
        $handler = 'toto';
        $instance = new $this->myclass($handler);
        $this->assertSame($handler, $instance->handler);
    }
    
    public function test___publicProperties()
    {
        $items = array('handler','config','code');
        foreach ($items as $item) {
            $prop = new ReflectionProperty($this->myclass, $item);
            $this->assertTrue($prop->isPublic());
        }
    }
    
    public function test_isActive()
    {
        $instance = new $this->myclass();
        
        $value = $instance->isActive();
        $this->assertSame(true, $value);
    }
    
    public function test_loadConfig()
    {
        $instance = new $this->myclass();
        
        $instance->loadConfig();
        $this->assertTrue(is_array($instance->config));
    }
    
    public function test_getCode()
    {
        $instance = new $this->myclass();
        
        $instance->code = 100;
        $value = $instance->getCode();
        $this->assertSame('100', $value);
    }
    
    public function test_render()
    {
        $instance = new $this->myclass();
        
        $value = $instance->render();
        $this->assertSame('', $value);
    }
    
    public function test_renderValidationJS()
    {
        $instance = new $this->myclass();
        
        $value = $instance->renderValidationJS();
        $this->assertSame('', $value);
    }
    
    public function test_verify()
    {
        $instance = new $this->myclass();
        
        $value = $instance->verify();
        $this->assertSame(false, $value);
    }
    
    public function test_verify100()
    {
        $instance = new $this->myclass();
        
        $sessionName = 'SESSION_NAME_';
        $_SESSION["{$sessionName}_code"] = 'toto';
        $_POST[$sessionName] = ' ToTo ';
        $value = $instance->verify($sessionName);
        $this->assertSame(true, $value);
        unset($_SESSION["{$sessionName}_code"], $_POST[$sessionName]);
    }
    
    public function test_verify200()
    {
        $instance = new $this->myclass();
        
        $sessionName = 'SESSION_NAME_';
        $_SESSION["{$sessionName}_code"] = 'toto';
        $_POST[$sessionName] = ' ToTo ';
        $instance->config['casesensitive'] = true;
        $value = $instance->verify($sessionName);
        $this->assertSame(false, $value);
        $_POST[$sessionName] = ' toto ';
        $value = $instance->verify($sessionName);
        $this->assertSame(true, $value);
        unset($_SESSION["{$sessionName}_code"], $_POST[$sessionName],$instance->config['casesensitive']);
    }
    
    public function test_destroyGarbage()
    {
        $instance = new $this->myclass();
        
        $value = $instance->destroyGarbage();
        $this->assertSame(true, $value);
    }
}
