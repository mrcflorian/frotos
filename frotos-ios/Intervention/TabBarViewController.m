
#import "TabBarViewController.h"
#import "HomeViewController.h"


@interface TabBarViewController ()

@end

@implementation TabBarViewController

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
	[_account update];
	// Do any additional setup after loading the view.
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void)setAccount:(InterventionAccount *)account
{
    _account = account;
    NSArray *controllers = [self viewControllers];
    for (id controller in controllers) {
        if ([controller isKindOfClass:[HomeViewController class]]) {
            HomeViewController *homeViewController = (HomeViewController*)controller;
            homeViewController.account = account;
        }
    }
}

@end
