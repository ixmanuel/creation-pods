# Object Building Dependencies
It manages object's dependencies for internal builders.


### Keywords
inversion control, dependency injection, factory method

### Goal
Testable and maintainable code by avoiding hard-coded refeferences in methods.

### Benefits
- Creating dependency objects only in convenience constructors.
- Avoiding a global dependency injection container.
- Switching default implementations with alternatives and test objects.

### Advantages of the language
- Referencing of classes and interfaces with its namespace.

### Alternatives
- Usage of mapping
- Anonymous classes.
- Passing empty objects that create those who are fully identifiable.

### Here is an example that reconstitutes an entity from a data store.
##### You just need to pass the interface and its implementation.


```php
    // Init - Alternative 1
    $person = new PersonInitFromData(
        new PersonFetched(1),
        new Pods(
            new Pod(Identity::class, ID::class),
            new Pod(About::class, AboutMe::class)
        )
    );  

    // Init - Alternative 2
    $person = new PersonInitFromData(
        new PersonFetched(1),
        (new Pods)
            ->define(Identity::class, ID::class),
            ->define(About::class, AboutMe::class)
        )
    );  


    // Definition
    final class PersonInitFromData implements Party
    {
        private $data;
        private $pods;

        public function __construct(PersonDataStore $data, CreationPods $pods)
        {
            $this->data = $data;

            $this->pods = $pods;
        }

        public function identity() : Identity
        {
            // The builder is called by its contract (interface) not by its concrete name.
            // It builds a new object in two stages, is more eloquent, but internally has 
            // to create two objects because pods is inmutable. See an alternative in
            // the next method.           
            return $this->pods
                            ->require(Identity::class)
                            ->new($data->name(), $data->birhtday());
        }

        public function about() : About
        {
            // It builds a new object from its contract.
            return $this->pods->require(About::class, $data->description(), $data->contact());
        }
    }  
```


### Alternative Naming for different communities of practice (CoP) 
#### It is just a proposal, not implemented yet.

###### Live thinking.
```php
    $person = new PersonInitFromData(
        new PersonFetched(1),
        new Soil(
            new Seed(Identity::class, ID::class),
            new Seed(About::class, AboutMe::class)
        )
    );    

    // $this->soil->for(Identity::class)->new(...)
    // $this->soil->new(Identity::class, ...)
```        

###### People thinking.
```php
    $person = new PersonInitFromData(
        new PersonFetched(1),
        new Crew(
            new Member(Identity::class, ID::class),
            new Member(About::class, AboutMe::class)
        )
    );  

    // $this->crew->require(Identity::class)->new(...)  
    // $this->crew->requireNew(Identity::class, ...)
```

###### Factory thinking.
```php
    $person = new PersonInitFromData(
        new PersonFetched(1),
        new Contracting (
            new Third(Identity::class, ID::class),
            new Third(About::class, AboutMe::class)
        )
    ); 
    // $this->contracting->require(Identity::class)->new(...)  
    // $this->contracting->requireNew(Identity::class, ...)    
```


### Proposal
###### The Php community adds the ability to implement this concept much as the composer use "require" for dependency management. It can use meaningful words, e.g., use, require, connect, provision; or re-use the "use" operator for traits:
```php
    // Definition
    final class PersonInitFromStore implements Party 
    {
        use Identity, About;

        public function identity() : Identity
        {
            return new Identity($data->name(), $data->birhtday());
        }        
    }

    // Usage
    $person = new PersonInitFromStore($store) use (PersonID, AboutMe);
    $person = new PersonInitFromStore($store) use (PersonID, AboutMe, AboutMeTest);
```    

Now, we can create new objects from its interfaces, therefore we don't have a hard-coded dependency, and the "new" operator within methods is not a headache anymore because our code is maintainable without complex mappings.
