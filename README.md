[![Build Status](https://img.shields.io/travis/ixmanuel/nexus/master.svg)](https://travis-ci.org/ixmanuel/nexus.svg)

# Mapping dependencies at construction time
This registers collaborators (dependencies) in the constructors in order to decouple the object creation in methods.

### Keywords
inversion control, dependency injection, builder

### Goal
Testable, reusable and maintainable code by avoiding hard-coded references in methods.

### Benefits
- Creating collaborators (a.k.a. dependencies) only in constructors.
- Avoiding a global dependency injection container.
- Switching default implementations with alternatives and test's objects.

### Advantages of the language
- Referencing of classes and interfaces with its namespace.

### Alternatives
- Usage of mapping.
- Anonymous classes.
- Passing empty objects that create those which are fully identifiable.
- Defining and implementing an interface for each collaboration.

### Here is an example that reconstitutes an entity from a data store.
###### You just need to pass the interface and its implementation.

```php
    // It reconstitutes a person from a data store: ID is a kind 
    // of Identity as much as an AboutMe is a kind of About and
    // both of them are collaborators. Take note that the 
    // creation is delayed until need it, but it is not 
    // a Singleton.
    $person = new PersonFromStore(
        new FetchedPerson($id),
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

        // Please, your ID?
        public function identity() : Identity
        {
            // It calls the main constructor or the operator "new" in the ID class.
            return $this->identity->new($this->record->key());
        }

        // Tell me something about you.
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
        // It can use another word, such as join, to avoid conflicts with traits.
        use (Identity, About);

        public function identity() : Identity
        {
            return new Identity($record->key());
        }

        ...               
    }

    // Client
    $person = new PersonFromStore($fetchedPerson) use (PersonID, AboutMe);
    // Test
    $person = new PersonFromStore($fetchedPerson) use (PersonIDTest, AboutMe);
```    

Now, we can create objects from their interfaces and thus, we have no more hard-coded dependencies in the methods.