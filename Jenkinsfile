pipeline {
  agent any
  environment {
    CI = 'true'
    REGISTRY_ADDRESS = credentials("REGISTRY_ADDRESS")
    IMAGE_TAG = sh(returnStdout: true, script: "echo '${env.BUILD_TAG}' | sed 's/%2F/-/g'").trim()
  }
  stages {
    stage("Init") {
      steps {
        sh "make init"
      }
    }
    stage("Test") {
      parallel {
        stage("Manager") {
          steps {
            sh "make manager-test"
          }
          post {
            failure {
              archiveArtifacts 'manager/var/log/**/*'
            }
          }
        }
      }
    }
    stage("Down") {
      steps {
        sh "make docker-down-clear"
      }
    }
    stage("Build") {
      steps {
        sh "make build"
      }
    }
    stage("Push") {
      steps {
        withCredentials([
          usernamePassword(
            credentialsId: "REGISTRY_AUTH",
            usernameVariable: "USER",
            passwordVariable: "PASSWORD"
          )
        ]) {
          sh "docker login -u=$USER -p=$PASSWORD"
        }
        sh "make push"
      }
    }
    stage("Prod") {
      steps {
        withCredentials([
          string(credentialsId: 'PRODUCTION_HOST', variable: 'HOST'),
          string(credentialsId: 'PRODUCTION_PORT', variable: 'PORT'),
          string(credentialsId: 'MANAGER_APP_SECRET', variable: 'MANAGER_APP_SECRET'),
          string(credentialsId: 'MANAGER_DB_PASSWORD', variable: 'MANAGER_DB_PASSWORD'),
          string(credentialsId: 'MANAGER_REDIS_PASSWORD', variable: 'MANAGER_REDIS_PASSWORD'),
          string(credentialsId: 'MANAGER_OAUTH_FACEBOOK_SECRET', variable: 'MANAGER_OAUTH_FACEBOOK_SECRET'),
          string(credentialsId: 'MANAGER_MAILER_URL', variable: 'MANAGER_MAILER_URL'),
          string(credentialsId: 'STORAGE_BASE_URL', variable: 'STORAGE_BASE_URL'),
          string(credentialsId: 'STORAGE_FTP_HOST', variable: 'STORAGE_FTP_HOST'),
          string(credentialsId: 'STORAGE_FTP_USERNAME', variable: 'STORAGE_FTP_USERNAME'),
          string(credentialsId: 'STORAGE_FTP_PASSWORD', variable: 'STORAGE_FTP_PASSWORD'),
          string(credentialsId: 'CENTRIFUGO_WS_HOST', variable: 'CENTRIFUGO_WS_HOST'),
          string(credentialsId: 'CENTRIFUGO_API_KEY', variable: 'CENTRIFUGO_API_KEY'),
          string(credentialsId: 'CENTRIFUGO_SECRET', variable: 'CENTRIFUGO_SECRET'),
          string(credentialsId: 'OAUTH_ENCRYPTION_KEY', variable: 'OAUTH_ENCRYPTION_KEY')
        ]) {
          sshagent(credentials: ['PRODUCTION_AUTH']) {
            sh "BUILD_NUMBER=${env.BUILD_NUMBER} make deploy"
          }
        }
      }
    }
  }
  post {
    always {
      sh "make docker-down-clear || true"
    }
  }
}
