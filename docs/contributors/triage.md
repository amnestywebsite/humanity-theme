# Welcome to the Triage Contribution Guide
Here you'll find information on how to get started with triaging issues for the project.  

## How we use GitHub

### Issues
We primarily use issues for bug reporting, and utilise discussions for planning new features.  

#### Issue Templates
We provide a limited set of issue templates, each of which is described below.

##### Bug Report
Users submitting bug reports should make use of the bug report issue template.  

##### Release Candidate
This template is for use only by the maintainers, and is used for collating issues into a future release.  

##### Epic
This template is for use only by the maintainers, and is used for collating issues into sets of defined goals.  
Epics are used for planning the roadmap.  

### Discussions
We categorise all discussions to help keep things organised. 

#### Announcements
This type of discussion is used primarily for the maintainers to announce changes that aren't related to new releases, such as information on the roadmap.  

#### Enhancement Requests
Houses all requests for brand new features.  

#### Feature Requests
Houses all requests for changes or enhancements to existing features.  

#### Q&A
This is a place for users to ask questions about the project.  

### Labels
All issues and pull requests should be marked with at least one label, but can be labelled with as many as are appropriate.  
The labels we use are described below:

| **Label**        | **Description**                            |
| ---------------- | ------------------------------------------ |
| bug              | Something isn't working as intended        |
| documentation    | Improvements or additions to documentation |
| duplicate        | This already exists                        |
| enhancement      | Improvements to existing features          |
| feature request  | Requests for new features                  |
| good first issue | Great for new contributors                 | 
| help wanted      | Extra attention is needed                  |
| translations     | String translation changes                 |
| accessibility    | Accessibility improvements or fixes        |
| invalid          | This seems incorrect                       |
| question         | Requires explanatory comments              |
| wontfix          | This won't be actioned                     |
| chore            | Neither a feature nor a bug fix            |
| epic             | This groups related issues together        |
| uat required     | This requires User Acceptance Testing      | 
| uat feedback     | This failed User Acceptance Testing        |
| uat approved     | This passed User Acceptance Testing        |

The following are primarily for use by the maintainers during the testing process, so can be skipped during triage:
- `epic`
- `uat required`
- `uat feedback`
- `uat approved`

## How we Triage

### Issues
When triaging issues, we ensure that the issue template has been filled out correctly, and that there is enough information contained therein for us to understand and reproduce it.  
We may add further labels at this point, or otherwise acknowledge the submission.  
If the issue submitted is a feature or enhancement request, or a question, we will convert it to a discussion.  
If an incorrect issue template has been used, we may ask the submitter to edit their submission to meet the requirements of the appropriate issue type.  
To find issues that have yet to be acknowledged, you can use [this filter](https://github.com/amnestywebsite/humanity-theme/issues?q=is%3Aissue+is%3Aopen+comments%3A0+).  

### Discussions
If a discussion has been created in an incorrect category, we will move it to the correct category (if valid), provided that the discussion has enough information to allow us to fully understand the submission.  
We add labels to new discussions, or otherwise acknowledge the submission.  
To find discussions that have yet to be acknowledged, you can use [this filter](https://github.com/amnestywebsite/humanity-theme/discussions?discussions_q=is%3Aopen+comments%3A0).  

### Pull Requests
When triaging pull requsts, we ensure that the pull request template has been filled out correctly, and that it includes links to the relevant issue(s) and/or discussions.  
This allows us to better understand the context around the submission.  
We may also label the pull request, or otherwise acknowledgeg it.  
