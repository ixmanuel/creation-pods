# Provision's pod
A dependency management that procures objects for further construction.

### Keywords
dependencies, dependency injection, inversion control, factory method

### Goal
Testable and maintainable code by avoiding the use of the new operator while languages ​​do not support such feature.

### Benefits
- Objects that instantiate other objects without "new" operator in their methods.
- Open dependency avoid a hard-coded one.
- Autonomus injection avoids global dependency-injection-container.
- Simple code, is maintainable code.

### Hacks because of limitation of the language
- Convenience constructors are implemented with static methods because php doesn't provide overloading, but it mimics the same functionallity, they return new objects. Thus, they are composable. (new Object) do the same as (Object::new)

### Advantages of the language
- Referencing of classes and interfaces with its namespace.

### Alternatives
- Usage of mapping or anonymous classes (tests cover this case).
- Passing empty objects that create those who are fully identifiable.

### Here is an example that reconstitutes an entity from a data store.
###### You just need to pass the interface and its implementation.

    $person = new PersonInitFromData(
        new PersonFetched(1),
        Pod::require([
            Identity::class => ID::class,
            About::class    => AboutMe::class
        ])
    );
    
    $this->assertTrue($person->identity()->name() == "Lorem Ipsum");
    $this->assertTrue($person->about()->contact() == "lorem@ip.sum");
    
    
    final class PersonInitFromData implements Party
    {
        private $personData;
        private $mother;

        public function __construct(PersonDataStore $personData, Model\OpenCreation $mother)
        {
            $this->personData = $personData;

            $this->mother = $mother;
        }

        public function identity() : Identity
        {
            return $this->mother->new(Identity::class, $personData->name(), $personData->birhtday());
        }

        public function about() : About
        {
            return $this->mother->new(About::class, $personData->description(), $personData->contact());
        }
    }

### A dream
###### The Php community adds the ability to implement this concept much as the composer use "require" for dependency management. It can use any word, e.g., use, require, connect, provision; or re-use the "use" operator for traits:

    // Definition
    final class PersonInitFromStore implements Party 
    {
        use Identity, About;

        public function identity() : Identity
        {
            return new Identity($personData->name(), $personData->birhtday());
        }        
    }

    // Usage
    $person = new PersonInitFromStore($store) use (PersonID, AboutMe);

Now, we can create new objects from its interfaces, therefore we don't have a hard-coded dependency, and the "new" operator within methods is not a headache anymore because our code is maintainable without complex mappings.
