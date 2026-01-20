# Technical Assessment

Welcome! Thank you for agreeing to take part in this technical assessment.

## Important Information

 - This is a take home technical assessment that we'd like you to complete in your own time before you're next interview.
 - We recommend that you spend a maximum of **2-3 hours** to complete this assessment.
 - You may complete this exercise in **any language** you wish, but please consider our technology stack when making your choice.
 - You may use this repository in any way you wish. You can create branches, commit your work, etc. We ask that at the end of the assessment all work is committed to the 'main' branch & that you've got clean commit messages.
 - You may add any additional documentation you wish to the repository. e.g. how to run the application and the desired output.
 - If you have any questions about the assessment feel free to contact Kadence before your scheduled interview time.

## Assessment - Validating and Sorting Addresses

We would like you to create a comparison function for sorting a collection of addresses in Northern Ireland.

For the purposes of this problem an address will be a single string, with commas separating each line and should follow the following conventions:
* An address **MUST** have a house number, with a street name on the same line
* An address **MAY** have a building name
* An address **MUST** have a post code on the last line
* An address **MAY** have a county. If it does, that line will start Co. or County
* An address **MUST** have a town on the line before the county
* In the case of an apartment, it is possible that two lines of the address will have a number. If this is the case, the 2nd number is the street address
* A valid postcode **MUST** start with BT, then a one or two digit number, then a space, then a single digit, then two letters
* Other than these lines, an address **MUST NOT** have any other lines
* An address **MUST** have a comma between each line

By application of these conventions, the following addresses are examples of valid input:
* 18 Ormeau Ave, Belfast, BT2 8HS
* 18 Ormeau Ave, Belfast, Co. Antrim, BT2 8HS
* Ormeau Baths, 18 Ormeau Ave, Belfast, BT2 8HS
* Ormeau Baths, 18 Ormeau Ave, Belfast, Co. Antrim, BT2 8HS
* 2.4 The Front, 36 Shore Road, Holywood, BT18 9GZ

The following addresses are examples of invalid input:
* Ormeau Baths, Belfast, BT2 8HS
* Ormeau Baths, 18 Ormeau Ave, Belfast, Co. Antrim
* Downing Street, London, SW1A 2AA
* 18 Ormeau Ave, Belfast, Co. Antrim BT2 8HS

Write a tool that can sort a collection of addresses logically, in the following order
* By Town/City alphabetically, in ascending order
* Then by street name alphabetically, in ascending order
* Then by street number in ascending order
* Then by property name alphabetically, in ascending order
* Then (in the case of an apartment) by property number, in ascending order

Your tool should throw an exception if any addresses are invalid.

You can supply the addresses to the application however you wish e.g. via a text file, inputting them manually in a text area on an HTML form etc. The example addresses you choose should showcase all the functionality of your application.

We recommend using a sorting function which is already built-in to your chosen programming language e.g. in PHP you could use usort or uasort.

## Submitted Project Implementation

See [IMPLEMENTATION.md](IMPLEMENTATION.md) for technical details, design decisions, and usage instructions.
