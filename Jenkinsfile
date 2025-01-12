pipeline {
    agent any

    environment {
        // Define any environment variables here if needed
        // Example:
        // MY_ENV_VAR = 'value'
    }

    stages {
        stage('Checkout SCM') {
            steps {
                echo 'Cloning repository...'
                checkout scm
            }
        }

        stage('Install Dependencies') {
            steps {
                echo 'Installing dependencies...'
                script {
                    // Install PHP dependencies (example for a PHP project)
                    // Modify this section as needed for your environment
                    sh '''
                        if ! php -m | grep -q 'dom'; then
                            echo "Installing missing PHP extensions..."
                            apt-get update && apt-get install -y php-xml
                            docker-php-ext-install dom simplexml
                        fi
                    '''
                    sh 'composer install --ignore-platform-req=ext-dom --ignore-platform-req=ext-simplexml'
                }
            }
        }

        stage('Run Tests') {
            steps {
                echo 'Running tests...'
                // Example test step, replace with your actual testing command
                sh 'phpunit --configuration phpunit.xml'
            }
        }

        stage('Build Docker Image') {
            steps {
                echo 'Building Docker image...'
                script {
                    // Example Docker build process
                    sh 'docker build -t my-app:${BUILD_NUMBER} .'
                }
            }
        }

        stage('Push Docker Image') {
            steps {
                echo 'Pushing Docker image...'
                script {
                    // Push the Docker image to a registry (replace with your registry)
                    sh 'docker push my-app:${BUILD_NUMBER}'
                }
            }
        }

        stage('Deploy') {
            steps {
                echo 'Deploying application...'
                script {
                    // Example deployment step with port mapping 8002:8000
                    sh 'docker run -d -p 8002:8000 my-app:${BUILD_NUMBER}'
                }
            }
        }
    }

    post {
        success {
            echo 'Build succeeded! Cleaning workspace...'
            cleanWs()  // Clean up workspace after successful build
        }
        failure {
            echo 'Build failed!'
        }
    }
}
