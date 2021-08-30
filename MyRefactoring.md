# My Followed Principles :
- Anticipate the works to do on the refactoring
- Refactor before adding update
- Write/Validate test before doing any refactoring => Avoid regressions
- Reformat :
   - Apply team norms to code => usually PSR
   - Rename variables/functions => make them more explicits
   - Add comments to explain the code if needed
- SOLID principles used here :
   - Single responsibility principle => Specialized classes/functions/methods
   - Interface segregation principle => Better to use multiple smaller specifics interfaces instead of generic interface
   - Dependency inversion principle => Low-level module have to stay independent of high-level module

# My anticipated steps :
 - Creating repository, first commit
 - Setting local environment, running the code/tests

 - Validating the tests + upgrading => No regression of the code after the refactoring (FunctionalTests)

 - Rewriting a bit of code without changing what it does.
    - Rename variables/functions
    - Adding getters/setters => private attributes (would probably not do it in the real code as it can need a lot of refactoring)
   
 - Removing lines 13-18 from LessonRepository (Will do => doesn't affect the class functionalities)
 - Reformat code from all classes without affecting the functionalities (Will do => doesn't affect the class functionalities)

 - Creating interface for each repository (Interface segregation principle) => Won't do => DO NOT MODIFY THIS CLASS
    
 - Creating new tests to match new features (here having new placeholders)
   
 - Rewrite/Optimize TemplateManager Code
    - Sending custom exception (Missing template => actually not reached in php7.4 with typed parameters)
    - Injecting dependencies with constructor, setting default value because no auto-wiring (Dependency inversion principle)
 
 - Making TemplateManager a manager / Extract business code in specialized service (Single responsibility principle)
        - One class to transform/formatting the data => TemplateDataTransformer
        - One class to replace placeholder by data => TextUtils:replacePlaceholders

 - Writings TUs for new classes
 - Writings TUs for TemplateManager

#My real steps

- Creating repository, first commit
- Setting local environment, running the code/tests

- Code Reformat

- Validating the tests + Adding new tests if needed => No regression of the code after the refactoring (FunctionalTests)

- Rewrite/Optimize/Fix TemplateManager