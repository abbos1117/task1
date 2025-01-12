pipeline {
    agent any
    stages {
        stage('Checkout Code') {
            steps {
                echo "Cloning repository..."
                checkout scm
            }
        }
        stage('Install Dependencies') {
            steps {
                echo "Installing PHP dependencies with Composer..."
                sh 'composer install'
            }
        }
        stage('Lint Code') {
            steps {
                echo "Running PHP lint checks..."
                sh 'php -l $(find . -type f -name "*.php")'

                echo "Running CodeSniffer checks..."
                sh 'vendor/bin/phpcs --standard=PSR12 src/'
            }
        }
        stage('Run Tests') {
            steps {
                echo "Running tests..."
                sh 'vendor/bin/phpunit'
            }
        }
        stage('Build Docker Image') {
            steps {
                echo "Building Docker image..."
                sh 'docker build -t your-image-name:latest .'
            }
        }
        stage('Run Docker Image') {
            steps {
                echo "Running Docker container..."
                sh 'docker run --rm -d -p 80:80 your-image-name:latest'
            }
        }
        stage('Push Docker Image') {
            steps {
                echo "Pushing Docker image to registry..."
                sh 'docker tag your-image-name:latest your-registry/your-image-name:latest'
                sh 'docker push your-registry/your-image-name:latest'
            }
        }
    }
    post {
        always {
            echo "Cleaning workspace..."
            cleanWs()
        }
        failure {
            echo "Build failed!"
        }
        success {
            echo "Build completed successfully!"
        }
    }
}
