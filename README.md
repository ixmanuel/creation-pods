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


