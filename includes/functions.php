<?php
    
    /**
     * This file contains functions used across all files.
     */
    
    /**
     * Class with useful functions
     *
     * @author Jhonattan Elías Prieto Morillo
     */
    abstract class FunctionsMountainConnect
    {
        /**
         * @var array Contains the file types that can be used when uploading a photo.
         */
        public const array UPLOAD_PHOTOS_FORMATS_ALLOWED = ['image/jpeg', 'image/png', 'image/jpg'];
        /**
         * @var int Max size of uploaded photos.
         * @internal The formula used is 2 * (1024 * 1024), PHP uses bytes for checking file size
         */
        public const int UPLOAD_PHOTOS_MAX_SIZE = 2097152; // 2 MB
        /**
         * @var string Path where photos are uploaded
         * @internal It should be mountain_connect/uploads/photos/
         */
        public const string UPLOAD_PHOTOS_PATH = __DIR__ . '/../uploads/photos/';
        /**
         * @var string Message to display when the form send doesn't correspond to any known module
         */
        private const string CRUD_INVALID_FORM_TYPE = 'Tipo de formulario inválido';
        /**
         * @var string Message to display when the form tries to perform an uknown action.
         */
        private const string CRUD_INVALID_FORM_ACTION = 'Acción no válida';
        /**
         * @var int Minimum password length when the user registers
         */
        public const int FORM_MIN_USER_PWD_LENGTH = 8;
        /**
         * @var array List of modules (folders) inside public/
         * @todo Use scandir() to dinamically fetch folders
         */
        private const array MODULES = [
            'climbing',
            'ferratas',
            'routes',
        ];
        
        /**
         * Adjusts routes inside of subfolders to prevent PAGE NOT FOUND errors.
         *
         * @param string $path Current route, usually obtained via $_SERVER['REQUEST_URI']
         *
         * @return string Number of steps back based on the route
         * @todo   Check for multiple subfolders
         */
        public static function generatePath(string $path): string
        {
            foreach (self::MODULES as $module) {
                if (str_contains($path, $module)) {
                    return "../";
                }
            }
            return '';
        }
        
        /**
         * Defines the logic to perform "Database" operations
         *
         * @param string $form_data   The module used (users, climbings...)
         * @param string $form_action Action to perform (Create, Update...)
         * @param array  $data        Data from the form ($_POST + $_FILES)
         * @param array  $database    "Database" data ($_SESSION)
         *
         * @return array
         */
        public static function crudAction(string $form_data, string $form_action, array $data, array $database): array
        {
            $result = [];
            try {
                switch ($form_data) {
                    case 'user':
                        switch ($form_action) {
                            case 'register':
                                $errors = self::validateUserCreation($data);
                                if (!empty($errors)) {
                                    $result['errors'] = $errors;
                                    throw new RuntimeException('Validation failed');
                                }
                                // Calculate the new user ID (auto-increment style)
                                $id = count($database['app']['users']) + 1;
                                $result = [
                                    'id'             => $id,
                                    'username'       => self::sanitizeString($data['username']),
                                    'email'          => self::sanitizeString($data['email']),
                                    'pwd'            => password_hash($data['pwd'], PASSWORD_DEFAULT, ['cost' => 15]),
                                    'experience'     => self::sanitizeString($data['experience']),
                                    'specialization' => self::sanitizeString($data['specialization']),
                                    'province'       => self::sanitizeString($data['province']),
                                    'active'         => true,
                                ];
                                break;
                            case 'login':
                                $user = htmlspecialchars($data['user']);
                                $password = htmlspecialchars($data['pwd']);
                                // Determine if we are using username or email, we check if the input contains '@'.
                                $is_email = str_contains($user, '@') === true;
                                /*
                                 * Look if the user exists, this code takes the whole array, looks for every user if the value from the form matches the password
                                 */
                                $user_auth = array_filter(
                                    $database['app']['users'],
                                    static fn($registered) => $user === ($is_email === true ? $registered['email'] : $registered['username']) && // Validate User or Email
                                                              password_verify($password, $registered['pwd'])
                                ); // Validate password
                                if (!empty($user_auth)) {
                                    $result = $user_auth[array_key_first($user_auth)]; // return the array values from inside the users array
                                }
                                break;
                            default:
                                throw new UnexpectedValueException(self::CRUD_INVALID_FORM_ACTION);
                        }
                        break;
                    case 'route':
                        switch ($form_action) {
                            case 'create':
                                $errors = self::validateRouteCreation($data);
                                if (!empty($errors)) {
                                    $result['errors'] = $errors;
                                    throw new RuntimeException('Validation failed');
                                }
                                $id = count($database['app']['routes']) + 1;
                                $result = [
                                    'id'             => $id,
                                    'name'           => self::sanitizeString($data['name']),
                                    'difficulty'     => self::sanitizeString($data['difficulty']),
                                    'distance'       => self::sanitizeString($data['distance']),
                                    'elevation'      => self::sanitizeString($data['elevation']),
                                    'duration'       => self::sanitizeString($data['duration']),
                                    'province'       => self::sanitizeString($data['province']),
                                    'season'         => $data['season'],        // Array
                                    'description'    => self::sanitizeString($data['description']),
                                    'tech_level'     => self::sanitizeString($data['tech_level']),
                                    'physical_level' => self::sanitizeString($data['physical_level']),
                                    'images'         => [], // We store the images later
                                ];
                                // Handle image storing
                                if (!empty($data['images']['name']) && is_array($data['images']['name'])) {
                                    foreach ($data['images']['name'] as $i => $original_name) {
                                        // Validate file type
                                        if (!in_array($data['images']['type'][$i], self::UPLOAD_PHOTOS_FORMATS_ALLOWED, true)) {
                                            continue; // Skip unsupported type
                                        }
                                        
                                        // Validate file size
                                        if ($data['images']['size'][$i] > self::UPLOAD_PHOTOS_MAX_SIZE) {
                                            continue; // Skip file too large
                                        }
                                        
                                        if ($data['images']['error'][$i] === UPLOAD_ERR_OK) {
                                            $final_name = self::generateRandomString($original_name);
                                            $dest = self::UPLOAD_PHOTOS_PATH . $final_name;
                                            if (move_uploaded_file($data['images']['tmp_name'][$i], $dest)) {
                                                $result['images'][] = $final_name;
                                            }
                                        }
                                    }
                                }
                                break;
                            default:
                                throw new UnexpectedValueException(self::CRUD_INVALID_FORM_ACTION);
                        }
                        break;
                    default:
                        throw new UnexpectedValueException(self::CRUD_INVALID_FORM_TYPE);
                }
            } catch (UnexpectedValueException) {
                // TODO: Add a validation if the internal switch logic fails, for now, it should never fail.
                die(__METHOD__ . ': Switch Logic has failed #' . __LINE__);
            } catch (Throwable) {
                return $result;
            }
            return $result;
        }
        
        /**
         * Validates the creation of users data
         *
         * @param array $data
         *
         * @return array
         * @throws UnexpectedValueException if any validation fails
         */
        private static function validateUserCreation(array $data): array
        {
            $errors = [];
            try {
                # 1-Validate no empty fields
                // Required fields
                $required_fields = [
                    'username'       => 'Usuario',
                    'email'          => 'Correo electrónico',
                    'pwd'            => 'Contraseña',
                    'pwd_c'          => 'Confirmar contraseña',
                    'experience'     => 'Nivel de experiencia',
                    'specialization' => 'Especialidad',
                    'province'       => 'Provincia',
                ];
                $errors = self::validateEmptyData($required_fields, $data);
                // Since we need all fields to be not empty, we throw here and don't validate the rest
                if (!empty($errors)) {
                    throw new RuntimeException('Validation Error');
                }
                
                # 2 - Validate email format
                if (!self::validateEmail($data['email'])) {
                    $errors['email'] = "El formato del correo electrónico no es válido o está vacío.";
                }
                
                # 3 - Validate password matching
                if ($data['pwd'] !== $data['pwd_c']) {
                    $errors['pwd_c'] = "Las contraseñas no coinciden.";
                }
                
                # 4 - Validate minimum password requirement
                if (!empty($data['pwd']) && strlen($data['pwd']) < self::FORM_MIN_USER_PWD_LENGTH) {
                    $errors['pwd'] = 'La contraseña debe tener al menos ' . self::FORM_MIN_USER_PWD_LENGTH . ' caracteres.';
                }
            } catch (Throwable) {
                return $errors;
            }
            return $errors;
        }
        
        /**
         * Validates the login process data
         *
         * @param array $data
         *
         * @return array
         */
        private static function validateUserLogin(array $data): array
        {
            $errors = [];
            try {
                $required_fields = [
                    'user' => 'Usuario/Email',
                    'pwd'  => 'Contraseña',
                ];
                $errors = self::validateEmptyData($required_fields, $data);
                
                // Since we need all fields to be not empty, we throw here and don't validate the rest
                if (!empty($errors)) {
                    throw new RuntimeException('Validation Error');
                }
                
            } catch (Throwable) {
                return $errors;
            }
            return $errors;
        }
        
        /**
         * Validates the creation of new routes
         *
         * @param array $data
         *
         * @return array
         */
        private static function validateRouteCreation(array $data): array
        {
            $errors = [];
            
            return $errors;
        }
        
        /** Cleans strings by removing whitepsaces at beginning/end and some special characters
         *
         * @param string $string
         *
         * @return string
         */
        public static function sanitizeString(string $string): string
        {
            return trim(htmlspecialchars($string));
        }
        
        /**
         * Validates an email, an email is valid if is not empty, contains an 'at' symbol (@) and passes the internal PHP validations via filter_var
         *
         * @param string $email
         *
         * @return bool
         * @see filter_var()
         */
        public static function validateEmail(string $email): bool
        {
            return !empty($email) && str_contains($email, '@') && filter_var($email, FILTER_VALIDATE_EMAIL);
        }
        
        /**
         * Generates a random string, if $append is provided, it concatenates it at the end
         *
         * @param string|null $append
         *
         * @return string
         */
        public static function generateRandomString(?string $append = ''): string
        {
            return uniqid(more_entropy: true) . "_{$append}";
        }
        
        /**
         * Returns error messages if there are misssing fields on the provided data
         *
         * @param array $data
         * @param array $required_fields
         *
         * @return array
         */
        public static function validateEmptyData(array $required_fields, array $data): array
        {
            $errors = [];
            foreach ($required_fields as $key => $label) {
                if (empty($data[$key])) {
                    $errors[$key] = "El campo \"$label\" es obligatorio.";
                }
            }
            return $errors;
        }
    }