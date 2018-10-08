# ZF ADVANCED Oct 2018

Where we left off: file:///D:/Repos/ZF-Level-2/Course_Materials/index.html#/2/31

## ERRATA
* file:///D:/Repos/ZF-Level-2/Course_Materials/index.html#/2/27: properties need to be public

## CLASS NOTES
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
