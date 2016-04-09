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

### Here is an example that reconstitutes an entity from a data store.
###### You just need to pass the interface and its implementation.

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
            return $this->pods->requireNew(Identity::class, $data->name(), $data->birhtday());
        }

        public function about() : About
        {
            // It builds a new object from its contract.
            return $this->pods->requireNew(
                About::class, 
                $data->description(), 
                $data->contact()
            );
        }
    }  
```

## Proposals

#### An eloquent way for creating dependencies:
```php
// It is more eloquent but would have to create two objects 
// because the solution is inmmutable.
return $this->pods
                ->require(Identity::class)
                ->new($data->name(), $data->birhtday());
```                

#### Alternative Naming for different communities of practice (CoP) 
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


#### A proposal for the php community.
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
