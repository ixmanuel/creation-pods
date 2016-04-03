# Open Creation
Creating dependency objects only in convenience constructors.

### Words
dependencies, dependency injection, inversion control, factory method

### Goal
Stop using "new" operator inside methods.

### Benefits
- Objects that instantiate other objects without "new" operator in their methods.
- Open dependency avoid a hard-coded one.
- Autonomus injection avoids global dependency-injection-container.
- Simple code is maintainable code.

### Hacks because of limitation of the language
- Convenience constructors are implemented with static methods because php doesn't provide overloading, but at the end, they always call to the main constructor. Thus, they are composable. (new Object) do the same as (Object::new)

### Advantages of the language
- Referencing of classes and interfaces with its namespace.

### Alternatives
- Usage of mapping or anonymous classes (tests cover this case).
- Passing empty objects that create those who are fully identifiable.

### Here is an example that reconstitutes an entity from a data store.
###### You just need to pass the interface and its implementation.

    $person = new PersonInitFromData(
        new PersonFetched(1),
        OpenCreation::initMaps([
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


