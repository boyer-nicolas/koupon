CHANGELOG
----------------------

[2023-05-19]

[2023-05-17]
 * docs(#readme) Implemented dependencies, getting started, deployment & Roadmap features
 * refactor(#ci) Removed older NodeJS version to include for NextJS retrocompatibility
 * 💡feat(#ci): Added install before build & test
 * feature(#ci) Added build & test commands
 * 💡feat(#Tests): Added shop & cart render test

[2023-05-16]
 * 💡feat(#Coupon): Behavior is now as expected
 * 💡feat(#Cart): Added nice loader
 * 💡feat(#Coupon): Can now apply, calculate price & remove
 * 💡feat(#Coupon): Now displaying reduction

[2023-05-15]
 * 💡feat(#backend): Add to cart now works seamlessly
 * 💡feat(#Cart): Can now add items & sum totCoupon basic features are implemented
 * 💡feat(#Backend): Clarified cart class with code aeration

[2023-05-14]
 * 💡feat(#backend): Added PHP testing library (Pest-
 * 💡feat(#Backend): Improved EventSourcing && removed session storage

[2023-05-13]

[2023-05-11]
 * 💡feat(#hooks): Added build on push
 * 💡feat(#EventSourcing): Implemented Cart event sourcing on add.
 * 💡feat(#Cart): API now saves & returns cart contents

[2023-05-10]
 * 💡feat(#backend): Readded a refactored (simplified) PHP API
 * 💡feat(#testing): Implemented the first working tests
 * 💡feat(#layout): Fixed footer folder location
 * 💡feat(#addToCart): Added modal upon cart addition for UX & betdebugging

[2023-05-01]
 * 💡feat(#hooks): Added build & test on post commit
 * 🪚 refactor(#backend): Removed from this repo to separate and use next w/vercel + php w/deployer
 * 💡feat(#tests): Implemented vitest + shop test & toasts
 * 💡feat(#content): Added cart/shop pages + cart/coupon methods
 * 🪚 refactor(#nextjs): Switched to new app directory && added sass preprocessoer

[2023-04-29]
 * 💡feat(#scripts): Added bin folder & jwt generator (work in progress)

[2023-04-24]

[2023-04-23]
 * 📖 docs(#Readme): Added base (description, getting started, automations)
 * 🪚 refactor(#router): Now mounting auth if not authenticated, not mounting the rest of the api for security reasons
