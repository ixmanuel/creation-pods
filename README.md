[![Build Status](https://img.shields.io/travis/ixmanuel/nexus/master.svg)](https://travis-ci.org/ixmanuel/nexus.svg)

# Mapping dependencies at construction time
It manages the creation of collaborators without hard-coding references in methods.

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
    // It reconstitutes a person from a data store with the contract 
    // Identity and its counterpart implementation ID and the About 
    // contract with its implementation AboutMe.
    // The creation is delayed until need it,
    // but it is not a Singleton.
    $person = new PersonFromStore(
        new FetchedPerson(1),
        new Assignment(Identity::class, ID::class),
        new Assignment(About::class, AboutMe::class)
    );  

    // Description
    final class PersonFromStore implements Party
    {
        private $record;
        private $identity;
        private $about;

        public function __construct(IdentityRecord $record, Assignable $identity, Assignable $about)
        {
            $this->record = $record;

            $this->identity = $identity;

            $this->about = $about;
        }

        public function identity() : Identity
        {
            // It calls the main constructor or new operator in the ID class.
            return $this->identity->new($record->key());
        }

        public function about() : About
        {
            // It calls a convenience constructor in the AboutMe class.
            return $this->about->withID($this->identity());
        }
    }  
```

### A proposal for the php community.
```php
    // Definition
    final class PersonFromStore implements Party 
    {
        // It can use another word to avoid conflicts with traits.
        use (Identity, About);

        public function identity() : Identity
        {
            return new Identity($record->key());
        }

        ...               
    }

    // Usage
    $person = new PersonFromStore($fetchedPerson) use (PersonID, AboutMe);
    // Test
    $person = new PersonFromStore($fetchedPerson) use (PersonIDTest, AboutMe);
```    

Now, we can create new objects from their interfaces. Thus, we have no more hard-coded dependency.