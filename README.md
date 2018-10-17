# ZF ADVANCED Oct 2018

NOTE TO SELF: VM ???

## HOMEWORK
* For Friday 19 Oct 2018
  * Lab: Delegators
* For Wednesday 17 Oct 2018
  * Lab: Form Security
  * Lab: Initializers
  * Lab: Abstract Factories
* For Monday 15 Oct 2018
  * Lab: Forms and Fieldsets
  * Lab: File Uploads
* For Friday 12 Oct 2018
  * Lab: Object Hydration and Database Operations
  * Lab: Table Module Relationships

## ERRATA/NOTES
* file:///D:/Repos/ZF-Level-2/Course_Materials/index.html#/2/27: properties need to be public
* file:///D:/Repos/ZF-Level-2/Course_Materials/index.html#/2/32: should be `$user->email` and `$user->password`
* file:///D:/Repos/ZF-Level-2/Course_Materials/index.html#/2/31: should mention also need `getArrayCopy()` for extract
* file:///D:/Repos/ZF-Level-2/Course_Materials/index.html#/2/35: why does Reflection hydrator not extract public props?
* file:///D:/Repos/ZF-Level-2/Course_Materials/index.html#/2/57: should mention put JS stuff just before </body>
* file:///D:/Repos/ZF-Level-2/Course_Materials/index.html#/2/62: consider "queuing up" the requests, and then performing an optimized single database query at the end
* file:///D:/Repos/ZF-Level-2/Course_Materials/index.html#/3/5:  did not clarify what is "@ANO" == Zend\Form\Annotation
* file:///D:/Repos/ZF-Level-2/Course_Materials/index.html#/3/6:  verify the syntax for multiple validators in one line: missing {} ???
* file:///D:/Repos/ZF-Level-2/Course_Materials/index.html#/3/19: Profile entity should include a property "twitter"
* file:///D:/Repos/ZF-Level-2/Course_Materials/index.html#/4/5:  overrides ARE recommended!
* file:///D:/Repos/ZF-Level-2/Course_Materials/index.html#/4/13: need to clearly identify where this code is run!
* file:///D:/Repos/ZF-Level-2/Course_Materials/index.html#/4/25: you also need to register this factory using "abstract_factories" key
* file:///D:/Repos/ZF-Level-2/Course_Materials/index.html#/4/45: CSRF to PostForm already done in Forms lab!

## CLASS NOTES
### Form Annotations
* Create (or find) an example using Annotations
* Better docs: https://docs.zendframework.com/zend-form/quick-start/#using-annotations

### Object Hydration and Extraction
* You could rewrite the `exchangeArray()` method to make it more generic as follows:
```
class User
{
    public $email = '';
    public $password = '';
    public function exchangeArray($data)
    {
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value)
            if (isset($data[$key])) $this->$key = $value;
    }
}
```

## LAB NOTES:
* Table Module / Entities
  * onlinemarket.work entity classes already complete!
* composer.json doesn't like "//"
