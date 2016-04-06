# Provision's pod
A dependency management that procures objects for further construction.

### Keywords
dependencies, dependency injection, inversion control, factory method

### Goal
Testable and maintainable code by avoiding the use of the new operator.

### Benefits
- Objects that instantiate other objects without "new" operator in their methods.
- Open dependency avoid a hard-coded one.
- Autonomus injection avoids global dependency-injection-container.
- Simple code, is maintainable code.

### Advantages of the language
- Referencing of classes and interfaces with its namespace.

### Alternatives
- Usage of mapping or anonymous classes.
- Passing empty objects that create those who are fully identifiable.

### Here is an example that reconstitutes an entity from a data store.
###### You just need to pass the interface and its implementation.

    $person = new PersonInitFromData(
        new PersonFetched(1),
        new Pods(
            new Pod(Identity::class, ID::class),
            new Pod(About::class, AboutMe::class)
        )
    );
    
    $this->assertTrue($person->identity()->name() == "Lorem Ipsum");
    $this->assertTrue($person->about()->contact() == "lorem@ip.sum");
    
    
    final class PersonInitFromData implements Party
    {
        private $personData;
        private $pods;

        public function __construct(PersonDataStore $personData, PodResolution $pods)
        {
            $this->personData = $personData;

            $this->pods = $pods;
        }

        public function identity() : Identity
        {
            return $this->pods->new(Identity::class, $personData->name(), $personData->birhtday());
        }

        public function about() : About
        {
            return $this->pods->new(About::class, $personData->description(), $personData->contact());
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
