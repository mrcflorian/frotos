
#import "HomeViewController.h"
#import <FacebookSDK/FacebookSDK.h>
#import "StatusCell.h"
#import "CreateInterventionViewController.h"

@interface HomeViewController ()

@end

@implementation HomeViewController

/* Aceasta metoda initializeaza view controller-ul */
- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
	
    // Init the API
    _api = [[InterventionAPI alloc] initWithInterventionAccount:_account];
    
    // Get statuses
    [_api getFeedWithCompletionBlock:^(NSDictionary *json) {
        NSLog(@"%@", json);
    }];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    StatusCell *cell = [tableView dequeueReusableCellWithIdentifier:@"FeedStatusCell"];
    
    cell.authorButton.titleLabel.text = [NSString stringWithFormat:@"%d", indexPath.row];
    cell.actionLabel.text = [NSString stringWithFormat:@"%d", 10 - indexPath.row];
    
    return cell;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    return 10;
}

- (IBAction)startCreateNewIntervention:(id)sender
{
    UINavigationController *controller = [[self storyboard] instantiateViewControllerWithIdentifier:@"NewInterventionNav"];
    CreateInterventionViewController *viewController = (CreateInterventionViewController*) controller.viewControllers.firstObject;
    viewController.api = _api;
    [self presentViewController:controller animated:YES completion:nil];
}

@end
