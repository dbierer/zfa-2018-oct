# ZF ADVANCED Oct 2018

NOTE TO SELF: VM ???

## HOMEWORK
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


## CLASS NOTES
### Form Annotations
* Create (or find) an example using Annotations

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
