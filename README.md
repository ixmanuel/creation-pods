# Open Creation
Creating dependency objects only in convenience constructors.

### Words
dependencies, dependency injection, inversion control, factory method

### Goal
Stop using operator new inside methods.

### Benefits
- Objects that instantiate other objects without new operator in their methods.
- Open dependency avoid a hard-coded one.
- Autonomus injection avoid global dependency-injection-container.
- Simple code is maintainable code.

### Hacks because of limitation of the language
- Convenience constructors are implemented with static methods but at the end, always call to the main constructor.

### Advantages of the language
- Referencing of classes and interfaces with its namespace


### Alternatives
- Usage of mapping or anonymous classes.
- Passing empty objects that create those who are fully identifiable.

### Here is an example that reconstitutes an entity from a data store.
###### You just need to pass the interface and its implementation.

    $person = new PersonInitFromData(
        new PersonFetched(1),
        OpenCreation::initFromSet([
            Identity::class => ID::class,
            About::class    => AboutMe::class
        ])
    );
    
    $this->assertTrue($person->identity()->name() == "Lorem Ipsum");
    $this->assertTrue($person->about()->contact() == "lorem@ip.sum");
    
    
    final class PersonInitFromData
    {
        private $personData;
        private $mother;

        public function __construct(PersonDataStore $personData, Model\Mother $mother)
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


