//
//  InterventionAccount.m
//  Intervention
//
//  Created by Florian Marcu on 3/20/14.
//  Copyright (c) 2014 Florian Marcu. All rights reserved.
//

#import "InterventionAccount.h"

@implementation InterventionAccount

- (id)initWithFacebookUserInfo:(id)user_info {
    _fbId = [user_info objectForKey:@"id"];
    _userId = [@"99" stringByAppendingString:_fbId];
    _firstName = [user_info objectForKey:@"first_name"];
    _lastName = [user_info objectForKey:@"last_name"];
    _birthday = [user_info objectForKey:@"birthday"];
    _email = [user_info objectForKey:@"email"];
    if ([[user_info objectForKey:@"gender"] isEqual: @"male"]) {
        _sex = @"1";
    } else {
        _sex = @"0";
    }
    _location = [user_info objectForKey:@"location"];
    _profilePicture = [[user_info objectForKey:@"picture"] objectForKey:@"url"];
    return self;
}

- (void)setFriendsWithFbResult:(id)result {
    NSMutableArray *friends = [[NSMutableArray alloc] init];
    NSArray *fbFriends = [result objectForKey:@"data"];
    for (id friend in fbFriends) {
        NSDictionary *dict = [NSDictionary dictionaryWithObjectsAndKeys:
                                [friend objectForKey:@"id"], @"id",
                                [friend objectForKey:@"name"], @"name",
                                nil
                                ];
        [friends addObject:dict];
    }
    _friends = friends;
}

@end
