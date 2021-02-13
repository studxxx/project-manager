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
  }
  post {
    always {
      sh "make docker-down-clear || true"
    }
  }
}
