# Object Building Dependencies
It manages object's dependencies for internal builders collaborators.

### Keywords
inversion control, dependency injection, factory method

### Goal
Testable and maintainable code by avoiding hard-coded references in methods.

### Benefits
- Creating dependency objects only in convenience constructors.
- Avoiding a global dependency injection container.
- Switching default implementations with alternatives and test objects.

### Advantages of the language
- Referencing of classes and interfaces with its namespace.

### Alternatives
- Usage of mapping.
- Anonymous classes.
- Passing empty objects that create those who are fully identifiable.
- Define and implement an interface for each collaboration.

### Here is an example that reconstitutes an entity from a data store.
###### You just need to pass the interface and its implementation.

```php
    // Init 
    $person = new PersonInitFromData(
        new PersonFetched(1),
        new WorkingWith(Identity::class, ID::class),
        new WorkingWith(About::class, AboutMe::class)
    );  

    // Definition
    final class PersonInitFromData implements Party
    {
        private $data;
        private $identity;
        private $about;

        public function __construct(PersonDataStore $data, Collaboration $identity, Collaboration $about)
        {
            $this->data = $data;

            $this->identity = $identity;

            $this->about = $about;
        }

        public function identity() : Identity
        {
            // It calls the new operator in the ID class.
            return $this->identity->new($data->name(), $data->birhtday());
        }

        public function about() : About
        {
            // It calls a convenience constructor in the AboutMe class.
            return $this->about->withDescription($data->description());
        }
    }  
```

### A proposal for the php community.
```php
    // Definition
    final class PersonInitFromStore implements Party 
    {
        // It can use another word to avoid conflicts with traits.
        use Identity, About;

        public function identity() : Identity
        {
            return new Identity($data->name(), $data->birhtday());
        }        
    }

    // Init 
    $person = new PersonInitFromStore($store) use (PersonID, AboutMe);
    $person = new PersonInitFromStore($store) use (PersonID, AboutMe, AboutMeTest);
```    

Now, we can create new objects from its interfaces, therefore we don't have a hard-coded dependency, and the "new" operator within methods is not a headache anymore because our code is maintainable without complex mappings.
