//
//  StatusCell.h
//  Intervention
//
//  Created by Florian Marcu on 3/21/14.
//  Copyright (c) 2014 Florian Marcu. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface StatusCell : UITableViewCell

@property (strong, nonatomic) IBOutlet UIImageView *imageView;
@property (strong, nonatomic) IBOutlet UIButton *authorButton;
@property (strong, nonatomic) IBOutlet UILabel *actionLabel;

@end
