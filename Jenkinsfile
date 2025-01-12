pipeline {
    agent any

    environment {
        // Define environment variables here if needed
    }

    stages {
        stage('Declarative: Checkout SCM') {
            steps {
                checkout scm
            }
        }

        stage('Git - Checkout') {
            steps {
                echo 'Cloning repository...'
                checkout scm
            }
        }

        stage('Install Dependencies') {
            steps {
                script {
                    echo 'Installing PHP dependencies...'
                    // Try installing PHP extensions before Composer install
                    sh '''
                        if ! php -m | grep -q 'dom'; then
                            echo "Installing missing PHP extensions (dom, simplexml)..."
                            apt-get update && apt-get install -y php-xml
                            docker-php-ext-install dom simplexml
                        fi
                    '''
                    // Then run Composer install with or without ignoring platform requirements
                    sh 'composer install --ignore-platform-req=ext-dom --ignore-platform-req=ext-simplexml'
                }
            }
        }

        stage('Lint Code') {
            steps {
                echo 'Running linting...'
                // Linting steps
            }
        }

        stage('Run Tests') {
            steps {
                echo 'Running tests...'
                // Test execution steps
            }
        }

        stage('Build Docker Image') {
            steps {
                echo 'Building Docker image...'
                // Docker build steps
            }
        }

        stage('Run Docker Image') {
            steps {
                echo 'Running Docker image...'
                // Docker run steps
            }
        }

        stage('Push Docker Image') {
            steps {
                echo 'Pushing Docker image...'
                // Docker push steps
            }
        }

        stage('Cleanup') {
            steps {
                echo 'Cleaning workspace...'
                cleanWs()
            }
        }
    }

    post {
        failure {
            echo 'Build failed!'
        }
    }
}
