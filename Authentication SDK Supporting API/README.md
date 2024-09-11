# Supporting API for Auth SDK

## Project Title

Supporting API for Auth SDK

## Project Description

This API supports the **Auth SDK** by providing the backend services necessary for user authentication, token management, OAuth integration, and role management. It is built using Laravel and is designed to handle various aspects of user authentication, including the generation and validation of JWT tokens, handling OAuth provider interactions, and managing user roles.

## Table of Contents

-   [Project Title](#project-title)
-   [Project Description](#project-description)
-   [Table of Contents](#table-of-contents)
-   [Project Overview](#project-overview)
    -   [Key Components](#key-components)
    -   [Setup Instructions](#setup-instructions)
        -   [Install Dependencies](#install-dependencies)
        -   [Configure Environment Variables](#configure-environment-variables)
        -   [Generate Application Key](#generate-application-key)
        -   [Run Database Migrations](#run-database-migrations)
        -   [Start the API Server](#start-the-api-server)
-   [API Endpoints](#api-endpoints)
-   [Conclusion](#conclusion)

## Project Overview

The Supporting API for Auth SDK provides essential backend services for user authentication, role management, and OAuth integrations. The API is built on the Laravel framework and ensures secure handling of user data and tokens. It includes various services and components designed to work seamlessly with the Auth SDK.

### Key Components

-   **OAuthService**

    -   Handles OAuth-related functionality, including redirecting to the provider, processing provider callbacks, and managing OAuth tokens.
-   **AuthProviderInterface**

    -   Defines a standardized contract for authentication providers, ensuring a consistent approach across different providers.
-   **ExceptionHandler**

    -   Centralized exception handling for the API, ensuring consistent and informative error responses.
-   **User Registration**

    -   Manages the creation of new user accounts with validation and error handling.
-   **User Login**

    -   Authenticates users, generates JWT access and refresh tokens, and handles authentication errors.
-   **Token Refresh**

    -   Allows users to refresh their access tokens using valid refresh tokens.
-   **Role Management**

    -   Facilitates the creation and management of user roles with proper validation and error handling.
-   **User**

    -   Represents the user entity, including necessary attributes and relationships for user management.
-   **UserToken**

    -   Manages user tokens, including generation, storage, updating, and refreshing.

### Setup Instructions

Follow these steps to set up and run the Supporting API:

#### Install Dependencies

Use Composer to install the required PHP dependencies:

`composer install`

#### Configure Environment Variables

Create a new `.env` file from the provided example:

`cp .env.example .env`

Edit the `.env` file to include your specific configuration settings, such as database credentials and API keys.

#### Generate Application Key

Generate a unique application key for your Laravel application:

`php artisan key:generate`

#### Run Database Migrations

Set up the database by running the necessary migrations:

`php artisan migrate`

#### Start the API Server

Launch the server to make the API operational:

`php artisan serve`

The API will be accessible at `http://localhost:8000` or your specified base URL.

## API Endpoints

### Authentication Endpoints

-   **POST /register**: Register a new user.
-   **POST /login**: Authenticate a user and return a JWT token.
-   **POST /add-user-role**: Add a role to a user (Admin access required).
-   **POST /auth/refresh**: Refresh an access token using a valid refresh token.

### OAuth Endpoints

-   **POST /oauth/refresh**: Refresh an access token using a valid OAuth refresh token.
-   **GET /auth/{provider}**: Redirect to the OAuth provider for authentication.
-   **GET /auth/{provider}/callback**: Handle the callback from the OAuth provider.

## Conclusion

The Supporting API for Auth SDK provides a robust and secure backend solution for managing user authentication, token handling, and OAuth integrations. By following the setup instructions, you can quickly deploy and utilize this API to handle various authentication and authorization scenarios within your applications. For any questions or issues, please refer to the documentation or reach out to the maintainers for support.
