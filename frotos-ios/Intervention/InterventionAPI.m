//
//  InterventionAPI.m
//  Intervention
//
//  Created by Florian Marcu on 3/20/14.
//  Copyright (c) 2014 Florian Marcu. All rights reserved.
//

#import "InterventionAPI.h"
#import "FacebookInteraction.h"

#define INTERVENTION_API_INTERVENTION_ENDPOINT @"http://interventionapp.com/api/intervention.php"
#define INTERVENTION_API_USERS_ENDPOINT @"http://interventionapp.com/api/users.php"
#define INTERVENTION_API_FEED_ENDPOINT @"http://interventionapp.com/api/feed.php"
#define INTERVENTION_API_AUTOCOMPLETE_ENDPOINT @"http://interventionapp.com/api/autocomplete.php"

#define INTERVENTION_DATA_GET_STATUSES @"action=statuses&user_id="
#define INTERVENTION_DATA_GET_FEED @"action=getFeed&user_id="
#define INTERVENTION_DATA_SEARCH_FRIENDS @"action=friends&user_id="

@implementation InterventionAPI

- (id)initWithInterventionAccount:(InterventionAccount *)account {
    _account = account;
    return self;
}

- (void)sendRequestWithURL:(NSString *)url params:(NSString *)params completionBlock:(void (^)(NSDictionary *))completionBlock
{
    NSData *sendData = [params dataUsingEncoding:NSASCIIStringEncoding allowLossyConversion:YES];
    NSMutableURLRequest *request = [[NSMutableURLRequest alloc] initWithURL:[NSURL URLWithString:url]];
    [request setHTTPMethod:@"POST"];
    [request setHTTPBody:sendData];
    
    //NSLog(@"%@", params);
    [NSURLConnection sendAsynchronousRequest:request
                                       queue:[NSOperationQueue mainQueue]
                           completionHandler:^(NSURLResponse *response, NSData *data, NSError *connectionError) {
                               NSDictionary* json = [NSJSONSerialization JSONObjectWithData:data
                                                                      options:0
                                                                        error:nil];
                               //NSLog(@"%@", json);
                               if (completionBlock) {
                                   completionBlock(json);
                               }
                           }
     ];
}

- (void)getMyStatusesWithCompletionBlock:(void (^)(NSDictionary *))completionBlock
{
    NSString *params = [INTERVENTION_DATA_GET_STATUSES stringByAppendingString:_account.userId];
    [self sendRequestWithURL:INTERVENTION_API_INTERVENTION_ENDPOINT params:params completionBlock:completionBlock];
}

- (void)getFeedWithCompletionBlock:(void (^)(NSDictionary *))completionBlock
{
    NSString *params = [INTERVENTION_DATA_GET_FEED stringByAppendingString:_account.userId];
    [self sendRequestWithURL:INTERVENTION_API_FEED_ENDPOINT params:params completionBlock:completionBlock];
}

- (void)searchFriendsWithString:(NSString *)query completionBlock:(void (^)(NSDictionary *))completionBlock
{
    NSString *tmpParams = [INTERVENTION_DATA_SEARCH_FRIENDS stringByAppendingString:_account.userId];
    NSString *tmpParams2 = [tmpParams stringByAppendingString:@"&query="];
    NSString *params = [tmpParams2 stringByAppendingString:query];

    [self sendRequestWithURL:INTERVENTION_API_AUTOCOMPLETE_ENDPOINT params:params completionBlock:completionBlock];
}


/**
    This method sends the current account to the API Server, in order to register/update the user's info.
 **/
- (void)registerOrUpdateUser
{
    NSMutableDictionary *dict = [[NSMutableDictionary alloc] init];
    [dict setObject:_account.fbId forKey:@"fb_id"];
    [dict setObject:_account.email forKey:@"email"];
    [dict setObject:_account.firstName forKey:@"firstname"];
    [dict setObject:_account.lastName forKey:@"lastname"];
    [dict setObject:_account.location forKey:@"location"];
    [dict setObject:_account.birthday forKey:@"birthday"];
    [dict setObject:_account.sex forKey:@"sex"];
    [dict setObject:_account.profilePicture forKey:@"profile_picture"];
    [dict setObject:_account.friends forKey:@"friends"];
    
    NSError *error;
    NSData *jsonData = [NSJSONSerialization dataWithJSONObject:dict
                                                       options:(NSJSONWritingOptions)0
                                                         error:&error];
    NSString *jsonString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
    NSString *params = [@"action=create&json_data=" stringByAppendingString:jsonString];
    [self sendRequestWithURL:INTERVENTION_API_USERS_ENDPOINT params:params completionBlock:nil];
    
    // Update friends' profile pictures
    for (id friend in _account.friends) {
        NSString *user_id = [friend objectForKey:@"id"];
        [FacebookInteraction getFriendProfilePictureWithUserId:user_id completionBlock:^(FBRequestConnection *fb, id result, NSError *err) {
            NSString *url = [[[result objectForKey:@"picture"] objectForKey:@"data"] objectForKey:@"url"];
            NSString *params = [NSString stringWithFormat:@"action=update_picture&user_id=%@&profile_picture=%@",user_id,url];
            [self sendRequestWithURL:INTERVENTION_API_USERS_ENDPOINT params:params completionBlock:nil];
        }];
    }
    
}

@end