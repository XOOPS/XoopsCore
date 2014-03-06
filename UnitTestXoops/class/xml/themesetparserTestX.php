<?php
require_once(dirname(__FILE__).'/../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsThemeSetParserTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsThemeSetParser';

    public function test___construct()
    {
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('SaxParser', $instance);
    }

    public function test_setThemeSetData()
    {
    }

    public function test_getThemeSetData()
    {
    }

    public function test_setImagesData()
    {
    }

    public function test_getImagesData()
    {
    }

    public function test_setTemplatesData()
    {
    }

    public function test_getTemplatesData()
    {
    }

    public function test_setTempArr()
    {
    }

    public function test_getTempArr()
    {
    }

    public function test_resetTempArr()
    {
    }
	
    public function test_ThemeSetDateCreatedHandler()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetDateCreatedHandler();
		$this->assertInstanceOf('ThemeSetDateCreatedHandler', $instance);
		$this->assertInstanceOf('XmlTagHandler', $instance);
    }

    public function test_ThemeSetDateCreatedHandler_getName()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetDateCreatedHandler();
		$instance->getName();
	}
	
    public function test_ThemeSetDateCreatedHandler_handleCharacterData()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetDateCreatedHandler();
		$instance->handleCharacterData(&$parser, &$data);
	}

    public function test_ThemeSetAuthorHandler()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetAuthorHandler();
		$this->assertInstanceOf('ThemeSetAuthorHandler', $instance);
		$this->assertInstanceOf('XmlTagHandler', $instance);
    }

    public function test_ThemeSetAuthorHandler_getName()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetAuthorHandler();
		$instance->getName();
	}
	
    public function test_ThemeSetAuthorHandler_handleBeginElement()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetAuthorHandler();
		$instance->handleBeginElement(&$parser, &$attributes);
	}

    public function test_ThemeSetAuthorHandler_handleEndElement()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetAuthorHandler();
		$instance->handleEndElement(&$parser, &$attributes);
	}

    public function test_ThemeSetDescriptionHandler()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetDescriptionHandler();
		$this->assertInstanceOf('ThemeSetDescriptionHandler', $instance);
		$this->assertInstanceOf('XmlTagHandler', $instance);
    }

    public function test_ThemeSetDescriptionHandler_getName()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetDescriptionHandler();
		$instance->getName();
	}
	
    public function test_ThemeSetDescriptionHandler_handleCharacterData()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetDescriptionHandler();
		$instance->handleCharacterData(&$parser, &$data);
	}

    public function test_ThemeSetGeneratorHandler()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetGeneratorHandler();
		$this->assertInstanceOf('ThemeSetGeneratorHandler', $instance);
		$this->assertInstanceOf('XmlTagHandler', $instance);
    }

    public function test_ThemeSetGeneratorHandler_getName()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetGeneratorHandler();
		$instance->getName();
	}
	
    public function test_ThemeSetGeneratorHandler_handleCharacterData()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetGeneratorHandler();
		$instance->handleCharacterData(&$parser, &$data);
	}

    public function test_ThemeSetNameHandler()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetNameHandler();
		$this->assertInstanceOf('ThemeSetNameHandler', $instance);
		$this->assertInstanceOf('XmlTagHandler', $instance);
    }

    public function test_ThemeSetNameHandler_getName()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetNameHandler();
		$instance->getName();
	}
	
    public function test_ThemeSetNameHandler_handleCharacterData()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetNameHandler();
		$instance->handleCharacterData(&$parser, &$data);
	}

    public function test_ThemeSetEmailHandler()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetEmailHandler();
		$this->assertInstanceOf('ThemeSetEmailHandler', $instance);
		$this->assertInstanceOf('XmlTagHandler', $instance);
    }

    public function test_ThemeSetEmailHandler_getName()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetEmailHandler();
		$instance->getName();
	}
	
    public function test_ThemeSetEmailHandler_handleCharacterData()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetEmailHandler();
		$instance->handleCharacterData(&$parser, &$data);
	}
	
    public function test_ThemeSetLinkHandler()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetLinkHandler();
		$this->assertInstanceOf('ThemeSetLinkHandler', $instance);
		$this->assertInstanceOf('XmlTagHandler', $instance);
    }

    public function test_ThemeSetLinkHandler_getName()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetLinkHandler();
		$instance->getName();
	}
	
    public function test_ThemeSetLinkHandler_handleCharacterData()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetLinkHandler();
		$instance->handleCharacterData(&$parser, &$data);
	}

    public function test_ThemeSetTemplateHandler()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetTemplateHandler();
		$this->assertInstanceOf('ThemeSetTemplateHandler', $instance);
		$this->assertInstanceOf('XmlTagHandler', $instance);
    }

    public function test_ThemeSetTemplateHandler_getName()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetTemplateHandler();
		$instance->getName();
	}
	
    public function test_ThemeSetTemplateHandler_handleBeginElement()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetTemplateHandler();
		$instance->handleBeginElement(&$parser, &$attributes);
	}

    public function test_ThemeSetTemplateHandler_handleEndElement()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetTemplateHandler();
		$instance->handleEndElement(&$parser, &$attributes);
	}

    public function test_ThemeSetImageHandler()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetImageHandler();
		$this->assertInstanceOf('ThemeSetImageHandler', $instance);
		$this->assertInstanceOf('XmlTagHandler', $instance);
    }

    public function test_ThemeSetImageHandler_getName()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetImageHandler();
		$instance->getName();
	}
	
    public function test_ThemeSetImageHandler_handleBeginElement()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetImageHandler();
		$instance->handleBeginElement(&$parser, &$attributes);
	}

    public function test_ThemeSetImageHandler_handleEndElement()
    {
		$x = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $x);
		
		$instance = new ThemeSetImageHandler();
		$instance->handleEndElement(&$parser, &$attributes);
	}
	
	
class ThemeSetModuleHandler extends XmlTagHandler
{
    public function test_getName()
    {
    }

    public function test_handleCharacterData(&$parser, &$data)
    {
    }
}

class ThemeSetFileTypeHandler extends XmlTagHandler
{
    public function test_getName()
    {
    }

    public function test_handleCharacterData(&$parser, &$data)
    {
    }
}

class ThemeSetTagHandler extends XmlTagHandler
{
    public function test_getName()
    {
    }

    public function test_handleCharacterData(&$parser, &$data)
    {
    }
}