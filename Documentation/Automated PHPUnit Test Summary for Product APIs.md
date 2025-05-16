## Automated PHPUnit Test Summary for Product APIs

This document summarizes the automated PHPUnit tests developed for the Product APIs of the Webshop Product Management System.

### 1. Main Objectives

The primary goals of these automated tests are to:

*   Verify the correct functionality of all CRUD (Create, Read, Update, Delete) operations for product resources.
*   Ensure that role-based access control (RBAC) is properly enforced, distinguishing between actions permitted for administrative users and regular users.
*   Validate that the API handles requests with invalid or missing data gracefully, returning appropriate validation error responses (e.g., HTTP 422).
*   Confirm that attempts to access or manipulate non-existent resources (e.g., a product with an ID that does not exist) result in standard HTTP 404 Not Found errors.
*   Check that unauthenticated users are prevented from accessing protected API endpoints, returning an HTTP 401 Unauthorized error.

### 2. Test Coverage

The tests cover the following API endpoints and scenarios:

**A. List Products (GET /api/products)**

*   **Admin/Authenticated User:**
    *   `test_index_withData_returnsProducts`: Verifies that an authenticated user (admin in the test setup) can successfully retrieve a list of all products. The test checks for a 200 OK status and the correct JSON structure for the product list.
*   **Unauthenticated User:**
    *   `test_unauthenticated_user_cannot_view_products`: Ensures that an attempt to retrieve products without authentication results in a 401 Unauthorized error.

**B. Show Product (GET /api/products/{id})**

*   **Admin:**
    *   `test_show_withData_returnsProductBySpecificID`: Confirms that an admin can retrieve a specific product by its ID, expecting a 200 OK status and the correct product data structure.
*   **Regular User:**
    *   `test_user_can_view_specific_product`: Tests if a regular user can view a specific product. Based on user feedback and policy, this test expects a 403 Forbidden status, indicating the user is authenticated but not authorized for this action.
*   **General:**
    *   `test_view_non_existent_product_returns_404`: Checks that requesting a product with a non-existent ID results in a 404 Not Found error.

**C. Create Product (POST /api/products)**

*   **Admin:**
    *   `test_admin_can_create_product`: Verifies that an admin can successfully create a new product with valid data, expecting a 201 Created status and confirmation that the product exists in the database.
    *   `test_create_product_with_invalid_data_returns_validation_errors`: Ensures that attempting to create a product with invalid data (e.g., missing name, non-numeric price) results in a 422 Unprocessable Entity status with corresponding validation error messages.
*   **Regular User:**
    *   `test_user_cannot_create_product`: Confirms that a regular user is not permitted to create a new product, expecting a 403 Forbidden status.

**D. Update Product (PUT /api/products/{id})**

*   **Admin:**
    *   `test_update_withData_updatesProductBySpecificID`: Checks that an admin can successfully update an existing product with valid data, expecting a 200 OK status and verifying the changes in the database.
    *   `test_update_non_existent_product_returns_404`: Ensures that attempting to update a product with a non-existent ID results in a 404 Not Found error.
*   **Regular User:**
    *   `test_user_cannot_update_product`: Verifies that a regular user cannot update an existing product, expecting a 403 Forbidden status.

**E. Delete Product (DELETE /api/products/{id})**

*   **Admin:**
    *   `test_admin_can_delete_product`: Confirms that an admin can successfully delete an existing product, expecting a 200 OK status (or 204 No Content, depending on implementation) and verifying the product is removed from the database.
    *   `test_delete_non_existent_product_returns_404`: Checks that attempting to delete a product with a non-existent ID results in a 404 Not Found error.
*   **Regular User:**
    *   `test_user_cannot_delete_product`: Ensures that a regular user is not permitted to delete an existing product, expecting a 403 Forbidden status.



### 3. Test Outcomes and User Feedback

The development of the automated tests involved an iterative process with user feedback, leading to the following key outcomes:

*   **Initial Implementation and Expansion:** The test suite was successfully expanded from an initial set of three tests to cover all CRUD operations (Create, Read, Update, Delete) for the Product APIs. This included tests for various scenarios, such as admin-only actions, regular user restrictions, handling of invalid data, and behavior with non-existent resources.

*   **User Collaboration and Corrections:**
    *   **Syntax Correction:** During the development, an initial version of the `ProductApiTest.php` file contained a minor syntax error (an extra parenthesis). This was identified and corrected by the user, ensuring the tests could run successfully.
    *   **Logic Correction for `test_user_can_view_specific_product`:** The user provided crucial feedback on the expected outcome for the `test_user_can_view_specific_product` test case. Initially, the test might have expected a 200 OK status. However, based on the project's specific authorization policies (where regular users are not permitted to view individual product details directly via the API), the user clarified that the test should assert a 403 Forbidden status. This correction was incorporated into the final version of the test suite, ensuring its accuracy in reflecting the intended application behavior.

*   **Successful Execution:** After incorporating the user's feedback and corrections, the user confirmed that the test suite (`ProductApiTest.php`) executed successfully in their local environment, with all tests passing according to the defined assertions and expected outcomes.

Overall, the collaborative process resulted in a comprehensive and accurate set of automated tests for the Product APIs, validating their functionality and security according to the project's requirements.
